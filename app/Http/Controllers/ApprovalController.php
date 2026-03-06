<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Mail\VendorPurchaseOrderMail;
use App\Mail\ApproverNotificationMail;
use App\Mail\RequesterStatusMail;
use App\Models\PurchaseRequisition;
use App\Models\PurchaseOrder;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApprovalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $requisitions = collect();

        if ($user->role === 'approver_stage1') {
            $requisitions = PurchaseRequisition::with(['department', 'user', 'selectedQuotation.vendor'])
                ->where('status', 'pending_stage1')
                ->get();
        } elseif ($user->role === 'approver_stage2') {
            $requisitions = PurchaseRequisition::with(['department', 'user', 'selectedQuotation.vendor'])
                ->where('status', 'pending_stage2')
                ->get();
        }

        return view('approvals.index', compact('requisitions'));
    }

    public function store(Request $request, PurchaseRequisition $requisition)
    {
        $request->validate([
            'status'   => 'required|in:approved,rejected',
            'comments' => 'nullable|string'
        ]);

        $user  = Auth::user();
        $stage = $user->role === 'approver_stage1' ? 'stage1' : 'stage2';

        // Security check
        if ($stage === 'stage1' && $requisition->status !== 'pending_stage1') return abort(403);
        if ($stage === 'stage2' && $requisition->status !== 'pending_stage2') return abort(403);

        // Record the approval
        $requisition->approvals()->create([
            'user_id'  => $user->id,
            'stage'    => $stage,
            'status'   => $request->status,
            'comments' => $request->comments
        ]);

        // ─── State Machine ────────────────────────────────────────────
        if ($request->status === 'rejected') {
            $requisition->update(['status' => 'rejected']);
            
            // Log & Notify
            AuditLog::record('rejected', "PR rejected at {$stage}", $requisition, ['comments' => $request->comments]);
            try {
                Mail::to($requisition->user->email)->send(new RequesterStatusMail($requisition, 'rejected', $stage, $request->comments));
            } catch (\Exception $e) { Log::error('Requester rejection email failed: '.$e->getMessage()); }

        } else {

            if ($stage === 'stage1') {
                $requisition->update(['status' => 'pending_stage2']);
                
                AuditLog::record('approved_stage1', "PR approved at Stage 1", $requisition, ['comments' => $request->comments]);
                
                // Notify Stage 2 approvers
                $stage2Approvers = User::where('role', 'approver_stage2')->get();
                foreach ($stage2Approvers as $approver) {
                    try {
                        Mail::to($approver->email)->send(new ApproverNotificationMail($requisition, 'stage2', $approver->name));
                    } catch (\Exception $e) { Log::error('Stage 2 approver email failed: '.$e->getMessage()); }
                }
                // Notify requester
                try {
                    Mail::to($requisition->user->email)->send(new RequesterStatusMail($requisition, 'approved', 'stage1', $request->comments));
                } catch (\Exception $e) { Log::error('Requester stage1 approval email failed: '.$e->getMessage()); }

            } elseif ($stage === 'stage2') {
                $requisition->update(['status' => 'po_generated']);
                AuditLog::record('approved_stage2', "PR approved at Stage 2 - PO Generated", $requisition, ['comments' => $request->comments]);

                $selectedQuote = $requisition->selectedQuotation;

                // ── PO Number: ACL/SRV/YYYY-YYYY+1/NNN ──────────────
                $year      = date('Y');
                $nextYear  = $year + 1;
                $sequence  = str_pad(PurchaseOrder::count() + 1, 3, '0', STR_PAD_LEFT);
                $poNumber  = "ACL/SRV/{$year}-{$nextYear}/{$sequence}";

                // ── Safe filename (slashes replaced with underscores) ─
                $safePoNumber = str_replace('/', '_', $poNumber);
                $pdfRelPath   = 'purchase_orders/' . $safePoNumber . '.pdf';

                // ── Upload token for vendor ──────────────────────────
                $uploadToken = Str::uuid()->toString();

                // ── Create PO Record ──────────────────────────────────
                $po = PurchaseOrder::create([
                    'purchase_requisition_id' => $requisition->id,
                    'quotation_id'            => $selectedQuote->id,
                    'vendor_id'               => $selectedQuote->vendor_id,
                    'po_number'               => $poNumber,
                    'status'                  => 'approved',
                    'total_amount'            => $selectedQuote->final_price,
                    'notes'                   => 'Auto-generated post Stage-2 approval.',
                    'upload_token'            => $uploadToken,
                ]);

                // ── Generate PO PDF ───────────────────────────────────
                Storage::disk('public')->makeDirectory('purchase_orders');
                $po->load(['vendor', 'purchaseRequisition']);
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.purchase_order', compact('po'));
                Storage::disk('public')->put($pdfRelPath, $pdf->output());

                Log::info("PO PDF generated at: storage/app/public/{$pdfRelPath}");

                // ── Send email to vendor ──────────────────────────────
                $uploadUrl   = url('/vendor/upload/' . $uploadToken);
                $vendorEmail = $po->vendor->email;

                if ($vendorEmail) {
                    try {
                        Mail::to($vendorEmail)
                            ->send(new VendorPurchaseOrderMail($po, $uploadUrl, $pdfRelPath));
                        Log::info("Vendor email sent to {$vendorEmail} for PO {$poNumber}");
                        AuditLog::record('po_emailed', "PO emailed to vendor {$vendorEmail}", $po);
                    } catch (\Exception $e) {
                        Log::error("Vendor email failed for PO {$poNumber}: " . $e->getMessage());
                    }
                } else {
                    Log::warning("No vendor email found for PO {$poNumber}");
                }
                
                // Notify requester PO generated
                try {
                    Mail::to($requisition->user->email)->send(new RequesterStatusMail($requisition, 'po_generated', 'stage2'));
                } catch (\Exception $e) { Log::error('Requester PO generated email failed: '.$e->getMessage()); }
            }
        }

        return back()->with('success', 'Approval action recorded successfully.');
    }
}
