<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseRequisition;
use Illuminate\Support\Facades\Auth;

class PurchaseRequisitionController extends Controller
{
    public function index()
    {
        $requisitions = PurchaseRequisition::with('department')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('requisitions.index', compact('requisitions'));
    }

    public function create()
    {
        return view('requisitions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $requisition = PurchaseRequisition::create([
            'user_id' => Auth::id(),
            'department_id' => Auth::user()->department_id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending_quotations',
        ]);

        return redirect()->route('requisitions.show', $requisition)
            ->with('success', 'Purchase Requisition created successfully. You can now upload quotations.');
    }

    public function show(PurchaseRequisition $requisition)
    {
        // Add auth check here if desired
        $requisition->load(['quotations.vendor', 'department', 'user', 'purchaseOrder']);
        return view('requisitions.show', compact('requisition'));
    }
}
