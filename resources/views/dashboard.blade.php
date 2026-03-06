<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 style="font-size:1.15rem;font-weight:700;margin:0">Dashboard</h1>
            <p style="font-size:0.8rem;color:#6b7280;margin:2px 0 0">Welcome back, {{ Auth::user()->name }}</p>
        </div>
    </x-slot>

    <div class="grid grid-4 section-gap">
        <div class="stat-card">
            <div>
                <div class="stat-label">Total Requisitions</div>
                <div class="stat-value">{{ $totalPRs }}</div>
                <div style="font-size:0.78rem;color:#6b7280">All time</div>
            </div>
            <div class="stat-icon" style="background:#eef2ff;color:#4f46e5"><i class="fas fa-file-alt"></i></div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-label">Pending Approval</div>
                <div class="stat-value" style="color:#d97706">{{ $pendingApproval }}</div>
                <div style="font-size:0.78rem;color:#6b7280">Awaiting decision</div>
            </div>
            <div class="stat-icon" style="background:#fef3c7;color:#d97706"><i class="fas fa-clock"></i></div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-label">PO Generated</div>
                <div class="stat-value" style="color:#4f46e5">{{ $poGenerated }}</div>
                <div style="font-size:0.78rem;color:#6b7280">Orders in progress</div>
            </div>
            <div class="stat-icon" style="background:#eef2ff;color:#4f46e5"><i class="fas fa-file-invoice"></i></div>
        </div>
        <div class="stat-card">
            <div>
                <div class="stat-label">Closed / Paid</div>
                <div class="stat-value" style="color:#059669">{{ $closed }}</div>
                <div style="font-size:0.78rem;color:#6b7280">Completed</div>
            </div>
            <div class="stat-icon" style="background:#d1fae5;color:#059669"><i class="fas fa-check-double"></i></div>
        </div>
    </div>

    @if($roleKpi)
    <div class="card section-gap" style="border-left:4px solid {{ $roleKpi['color'] }};border-radius:12px">
        <div class="card-header" style="background:{{ $roleKpi['bg'] }};border-radius:8px 8px 0 0">
            <div style="display:flex;align-items:center;gap:0.75rem">
                <div style="width:38px;height:38px;background:{{ $roleKpi['color'] }};border-radius:9px;display:flex;align-items:center;justify-content:center;color:white;font-size:16px">
                    <i class="{{ $roleKpi['icon'] }}"></i>
                </div>
                <div>
                    <div style="font-size:0.75rem;font-weight:600;color:{{ $roleKpi['color'] }};text-transform:uppercase;letter-spacing:0.05em">Action Required</div>
                    <div style="font-size:1.4rem;font-weight:800;color:#111827;line-height:1">{{ $roleKpi['value'] }}</div>
                    <div style="font-size:0.8rem;color:#6b7280">{{ $roleKpi['label'] }}</div>
                </div>
            </div>
            <a href="{{ $roleKpi['link'] }}" class="btn btn-primary btn-sm">
                View All <i class="fas fa-arrow-right" style="font-size:10px"></i>
            </a>
        </div>
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h2>Recent Requisitions</h2>
            @if(in_array(Auth::user()->role, ['admin','requester']))
            <a href="{{ route('requisitions.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> New PR</a>
            @endif
        </div>
        @if($recent->count())
        <div class="table-wrap" style="border:none;border-radius:0">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Requester</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($recent as $pr)
                @php
                    $statusBadge = match($pr->status) {
                        'draft' => ['badge-gray', 'Draft'],
                        'pending_quotations' => ['badge-yellow', 'Getting Quotes'],
                        'quotation_selected' => ['badge-indigo', 'Quote Selected'],
                        'pending_stage1' => ['badge-yellow', 'Stage 1 Review'],
                        'pending_stage2' => ['badge-yellow', 'Stage 2 Review'],
                        'po_generated' => ['badge-indigo', 'PO Generated'],
                        'invoice_uploaded' => ['badge-indigo', 'Invoice Received'],
                        'rejected' => ['badge-red', 'Rejected'],
                        'closed' => ['badge-green', 'Closed'],
                        default => ['badge-gray', ucfirst($pr->status)]
                    };
                @endphp
                <tr>
                    <td style="font-weight:500">{{ $pr->title }}</td>
                    <td>{{ $pr->user->name }}</td>
                    <td>{{ $pr->department->name ?? '—' }}</td>
                    <td><span class="badge {{ $statusBadge[0] }}">{{ $statusBadge[1] }}</span></td>
                    <td style="color:#6b7280">{{ $pr->created_at->format('M d, Y') }}</td>
                    <td><a href="{{ route('requisitions.show', $pr) }}" style="color:#4f46e5;font-size:0.8rem;font-weight:600;text-decoration:none">View →</a></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-inbox"></i></div>
                <h3>No Requisitions Yet</h3>
                <p>Get started by creating your first purchase requisition.</p>
            </div>
        @endif
    </div>
</x-app-layout>
