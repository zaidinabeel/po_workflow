<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseRequisition;
use App\Models\Invoice;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ── Global stats shown to all roles ──
        $totalPRs        = PurchaseRequisition::count();
        $pendingApproval = PurchaseRequisition::whereIn('status', ['pending_stage1', 'pending_stage2'])->count();
        $poGenerated     = PurchaseRequisition::where('status', 'po_generated')->count();
        $closed          = PurchaseRequisition::where('status', 'closed')->count();

        // ── Role-specific KPI ──
        $roleKpi = match($user->role) {
            'approver_stage1' => [
                'label' => 'Awaiting Your Approval',
                'value' => PurchaseRequisition::where('status', 'pending_stage1')->count(),
                'color' => '#d97706',
                'bg'    => '#fef3c7',
                'icon'  => 'fas fa-user-check',
                'link'  => route('approvals.index'),
            ],
            'approver_stage2' => [
                'label' => 'Awaiting Your Approval',
                'value' => PurchaseRequisition::where('status', 'pending_stage2')->count(),
                'color' => '#7c3aed',
                'bg'    => '#ede9fe',
                'icon'  => 'fas fa-user-shield',
                'link'  => route('approvals.index'),
            ],
            'accounts' => [
                'label' => 'Invoices for Review',
                'value' => Invoice::where('status', 'accounts_review')->count(),
                'color' => '#059669',
                'bg'    => '#d1fae5',
                'icon'  => 'fas fa-receipt',
                'link'  => route('invoices.index'),
            ],
            'requester' => [
                'label' => 'My Open Requisitions',
                'value' => PurchaseRequisition::where('user_id', $user->id)->whereNotIn('status', ['closed','rejected'])->count(),
                'color' => '#4f46e5',
                'bg'    => '#eef2ff',
                'icon'  => 'fas fa-file-alt',
                'link'  => route('requisitions.index'),
            ],
            default => null,
        };

        $recent = PurchaseRequisition::with(['user', 'department'])->latest()->limit(8)->get();

        return view('dashboard', compact('totalPRs', 'pendingApproval', 'poGenerated', 'closed', 'recent', 'roleKpi'));
    }
}
