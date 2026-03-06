<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 style="font-size:1.15rem;font-weight:700;margin:0">Pending Approvals</h1>
            <p style="font-size:0.8rem;color:#6b7280;margin:2px 0 0">Review and action purchase requisitions awaiting your approval</p>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="alert alert-success section-gap"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    @if($requisitions->count() > 0)
        <div style="display:flex;flex-direction:column;gap:1.25rem">
            @foreach($requisitions as $req)
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2 style="margin-bottom:2px">{{ $req->title }}</h2>
                        <div style="font-size:0.78rem;color:#6b7280">
                            Requested by <strong style="color:#374151">{{ $req->user->name }}</strong>
                            &bull; {{ $req->department->name ?? 'No Dept' }}
                            &bull; {{ $req->created_at->format('M d, Y') }}
                        </div>
                    </div>
                    <span class="badge badge-yellow"><i class="fas fa-clock"></i> Awaiting Your Approval</span>
                </div>
                <div class="card-body">
                    <div class="grid grid-2" style="gap:1.25rem">
                        <div>
                            <div class="form-label" style="color:#6b7280;margin-bottom:0.35rem">Justification</div>
                            <p style="margin:0;line-height:1.6;color:#374151;font-size:0.875rem;white-space:pre-wrap">{{ $req->description ?: 'No description provided.' }}</p>
                        </div>

                        @if($req->selectedQuotation)
                        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:1rem">
                            <div style="font-size:0.78rem;font-weight:600;color:#065f46;margin-bottom:0.5rem;text-transform:uppercase;letter-spacing:0.05em">Selected Quotation</div>
                            <div style="font-size:1.75rem;font-weight:800;color:#111827;margin-bottom:0.25rem">₹{{ number_format($req->selectedQuotation->final_price, 2) }}</div>
                            <div style="font-weight:600;color:#4f46e5;font-size:0.875rem">{{ $req->selectedQuotation->vendor->name }}</div>
                            <div style="font-size:0.78rem;color:#6b7280;margin-top:4px">{{ $req->selectedQuotation->vendor->email }}</div>
                            <a href="{{ asset('storage/' . $req->selectedQuotation->file_path) }}" target="_blank" style="display:inline-flex;align-items:center;gap:0.35rem;margin-top:0.75rem;font-size:0.8rem;font-weight:600;color:#4f46e5;text-decoration:none">
                                <i class="fas fa-file-pdf" style="color:#dc2626"></i> View Quotation PDF
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                <div style="padding:1rem 1.5rem;background:#f9fafb;border-top:1px solid #f3f4f6;border-radius:0 0 12px 12px">
                    <form action="{{ route('approvals.store', $req) }}" method="POST" style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap">
                        @csrf
                        <label style="font-size:0.8rem;font-weight:600;color:#374151">Comments (Optional)</label>
                        <input type="text" name="comments" placeholder="Add any notes..." class="form-control" style="flex:1;min-width:200px">
                        <button type="submit" name="status" value="rejected" class="btn btn-danger">
                            <i class="fas fa-times"></i> Reject
                        </button>
                        <button type="submit" name="status" value="approved" class="btn btn-success">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="card">
            <div class="empty-state">
                <div class="empty-icon" style="background:#d1fae5;color:#059669"><i class="fas fa-check-double"></i></div>
                <h3>All Caught Up!</h3>
                <p>No requisitions are pending your approval right now.</p>
            </div>
        </div>
    @endif
</x-app-layout>
