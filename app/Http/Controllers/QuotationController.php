<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PurchaseRequisition;
use App\Models\Quotation;
use App\Models\Vendor;
use App\Models\User;
use App\Models\AuditLog;
use App\Mail\ApproverNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class QuotationController extends Controller
{
    public function store(Request $request, PurchaseRequisition $requisition)
    {
        $request->validate([
            'vendor_name' => 'required|string|max:255',
            'vendor_email' => 'nullable|email|max:255',
            'vendor_phone' => 'nullable|string|max:50',
            'final_price' => 'required|numeric|min:0',
            'quotation_pdf' => 'required|mimes:pdf|max:10240', // max 10MB
        ]);

        // Find or create Vendor
        $vendor = Vendor::firstOrCreate(
            ['email' => $request->vendor_email],
            [
                'name' => $request->vendor_name,
                'phone' => $request->vendor_phone,
            ]
        );

        // Upload PDF
        $path = $request->file('quotation_pdf')->store('quotations', 'public');

        // Check if this new quote is the system recommended (lowest price)
        // Let's reset all first if this is strictly the lowest
        $currentLowest = $requisition->quotations()->orderBy('final_price', 'asc')->first();
        $isRecommended = false;
        
        if (!$currentLowest || $request->final_price < $currentLowest->final_price) {
            $isRecommended = true;
            // Unmark others
            $requisition->quotations()->update(['is_system_recommended' => false]);
        }

        Quotation::create([
            'purchase_requisition_id' => $requisition->id,
            'vendor_id' => $vendor->id,
            'file_path' => $path,
            'final_price' => $request->final_price,
            'is_system_recommended' => $isRecommended,
        ]);

        return back()->with('success', 'Quotation uploaded successfully.');
    }

    public function select(Request $request, PurchaseRequisition $requisition)
    {
        $request->validate([
            'quotation_id' => 'required|exists:quotations,id',
        ]);

        // Ensure quotation belongs to the requisition
        $quotation = Quotation::where('id', $request->quotation_id)
            ->where('purchase_requisition_id', $requisition->id)
            ->firstOrFail();

        // Mark it as selected
        $requisition->quotations()->update(['is_selected' => false]); // Unselect all
        $quotation->update(['is_selected' => true]);

        // Update Requisition status
        $requisition->update([
            'selected_quotation_id' => $quotation->id,
            'status' => 'pending_stage1', // Move to Stage 1 Approvals (Compliance)
        ]);

        AuditLog::record('quotation_selected', "Quotation from {$quotation->vendor->name} selected", $requisition, ['price' => $quotation->final_price]);

        // Notify Stage 1 approvers
        $stage1Approvers = User::where('role', 'approver_stage1')->get();
        foreach ($stage1Approvers as $approver) {
            try {
                Mail::to($approver->email)->send(new ApproverNotificationMail($requisition, 'stage1', $approver->name));
            } catch (\Exception $e) { Log::error('Stage 1 approver email failed: '.$e->getMessage()); }
        }

        return back()->with('success', 'Quotation selected! Requisition moved to Stage 1 Approvals.');
    }
}
