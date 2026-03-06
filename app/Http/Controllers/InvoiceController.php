<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Invoice;
use App\Models\PurchaseOrder;
use App\Models\Vendor;
use App\Models\AuditLog;
use App\Mail\VendorPaymentConfirmationMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    /**
     * Public vendor upload page — no auth required (token based)
     */
    public function vendorShow(string $token)
    {
        $po = PurchaseOrder::with(['vendor', 'purchaseRequisition'])->where('upload_token', $token)->firstOrFail();
        
        if ($po->invoice) {
            return view('invoices.vendor_already_uploaded', compact('po'));
        }
        
        return view('invoices.vendor_upload', compact('po'));
    }

    /**
     * Handle the vendor's invoice upload (public)
     */
    public function vendorStore(Request $request, string $token)
    {
        $po = PurchaseOrder::where('upload_token', $token)->firstOrFail();
        
        if ($po->invoice) {
            return back()->with('error', 'Invoice already uploaded for this Purchase Order.');
        }

        $request->validate([
            'invoice_pdf' => 'required|mimes:pdf|max:10240',
            'notes'       => 'nullable|string',
        ]);

        $path = $request->file('invoice_pdf')->store('invoices', 'public');

        Invoice::create([
            'purchase_order_id' => $po->id,
            'vendor_id'         => $po->vendor_id,
            'file_path'         => $path,
            'status'            => 'accounts_review',
            'notes'             => $request->notes,
        ]);

        $po->purchaseRequisition()->update(['status' => 'invoice_uploaded']);

        return view('invoices.vendor_success', compact('po'));
    }

    /**
     * Accounts dashboard — list invoices with optional status filter
     */
    public function index(Request $request)
    {
        $filter = $request->get('status', 'pending'); // default: show pending

        $query = Invoice::with([
            'purchaseOrder.vendor',
            'purchaseOrder.purchaseRequisition',
            'vendor'
        ])->orderBy('created_at', 'desc');

        if ($filter === 'pending') {
            $query->where('status', 'accounts_review');
        } elseif ($filter === 'paid') {
            $query->where('status', 'paid');
        }
        // 'all' = no filter

        $invoices     = $query->get();
        $pendingCount = Invoice::where('status', 'accounts_review')->count();
        $paidCount    = Invoice::where('status', 'paid')->count();
        $totalCount   = Invoice::count();

        return view('invoices.index', compact('invoices', 'filter', 'pendingCount', 'paidCount', 'totalCount'));
    }

    /**
     * Mark invoice as Paid/Closed
     */
    public function markPaid(Request $request, Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return back()->with('success', 'Invoice is already marked as Paid.');
        }

        $request->validate(['notes' => 'nullable|string|max:500']);

        $invoice->update([
            'status'  => 'paid',
            'paid_at' => now(),
            'notes'   => $request->notes ?: $invoice->notes,
        ]);

        // Update the parent PR to closed
        try {
            if ($invoice->purchaseOrder && $invoice->purchaseOrder->purchaseRequisition) {
                $invoice->purchaseOrder->purchaseRequisition->update(['status' => 'closed']);
            }
        } catch (\Exception $e) {
            Log::error('Could not close PR after invoice paid: ' . $e->getMessage());
        }

        try {
            AuditLog::record('invoice_paid', "Invoice #{$invoice->id} marked as Paid. Ref: {$request->notes}", $invoice);
        } catch (\Exception $e) {
            Log::error('AuditLog failed for invoice_paid: ' . $e->getMessage());
        }

        // ── Send payment confirmation email to vendor ──────────────────
        try {
            $vendor      = $invoice->purchaseOrder->vendor ?? $invoice->vendor;
            $vendorEmail = $vendor?->email;
            if ($vendorEmail) {
                Mail::to($vendorEmail)->send(
                    new VendorPaymentConfirmationMail($invoice, $request->notes ?? '')
                );
                Log::info("Payment confirmation email sent to {$vendorEmail}");
            }
        } catch (\Exception $e) {
            Log::error('Vendor payment email failed: ' . $e->getMessage());
        }

        return back()->with('success', '✅ Invoice marked as Paid and confirmation email sent to vendor.');
    }

    /**
     * Mark invoice as pending (undo payment)
     */
    public function markPending(Request $request, Invoice $invoice)
    {
        $invoice->update([
            'status'  => 'accounts_review',
            'paid_at' => null,
        ]);

        try {
            if ($invoice->purchaseOrder && $invoice->purchaseOrder->purchaseRequisition) {
                $invoice->purchaseOrder->purchaseRequisition->update(['status' => 'invoice_uploaded']);
            }
        } catch (\Exception $e) {
            Log::error('Could not revert PR after mark pending: ' . $e->getMessage());
        }

        try {
            AuditLog::record('invoice_pending', "Invoice #{$invoice->id} moved back to Pending Payment.", $invoice);
        } catch (\Exception $e) {}

        return back()->with('success', 'Invoice moved back to Pending.');
    }
}
