<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 style="font-size:1.15rem;font-weight:700;margin:0">My Requisitions</h1>
            <p style="font-size:0.8rem;color:#6b7280;margin:2px 0 0">All purchase requisitions you have submitted</p>
        </div>
    </x-slot>
    <x-slot name="headerActions">
        <a href="{{ route('requisitions.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Requisition
        </a>
    </x-slot>

    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <div class="card">
        @if($requisitions->count())
        <div class="table-wrap" style="border:none;border-radius:0">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Department</th>
                        <th>Quotations</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($requisitions as $req)
                @php
                    $statusBadge = match($req->status) {
                        'draft' => ['badge-gray', 'Draft'],
                        'pending_quotations' => ['badge-yellow', 'Getting Quotes'],
                        'quotation_selected' => ['badge-indigo', 'Quote Selected'],
                        'pending_stage1' => ['badge-yellow', 'Stage 1 Review'],
                        'pending_stage2' => ['badge-yellow', 'Stage 2 Review'],
                        'po_generated' => ['badge-indigo', 'PO Generated'],
                        'invoice_uploaded' => ['badge-indigo', 'Invoice Received'],
                        'rejected' => ['badge-red', 'Rejected'],
                        'closed' => ['badge-green', 'Paid & Closed'],
                        default => ['badge-gray', ucfirst($req->status)]
                    };
                @endphp
                <tr>
                    <td style="font-weight:500;color:#111827">{{ $req->title }}</td>
                    <td>{{ $req->department->name ?? '—' }}</td>
                    <td>
                        <span class="badge badge-gray">{{ $req->quotations->count() }} uploaded</span>
                    </td>
                    <td><span class="badge {{ $statusBadge[0] }}">{{ $statusBadge[1] }}</span></td>
                    <td style="color:#6b7280;font-size:0.8rem">{{ $req->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('requisitions.show', $req) }}" class="btn btn-secondary btn-sm">
                            View <i class="fas fa-chevron-right" style="font-size:10px"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-file-alt"></i></div>
                <h3>No Requisitions Found</h3>
                <p>Start by creating a new purchase requisition.</p>
                <br>
                <a href="{{ route('requisitions.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> New Requisition</a>
            </div>
        @endif
    </div>
</x-app-layout>
