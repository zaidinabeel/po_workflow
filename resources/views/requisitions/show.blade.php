<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 style="font-size:1.15rem;font-weight:700;margin:0">{{ $requisition->title }}</h1>
            <p style="font-size:0.8rem;color:#6b7280;margin:2px 0 0">
                <a href="{{ route('requisitions.index') }}" style="color:#4f46e5;text-decoration:none">My Requisitions</a>
                &rsaquo; PR Detail
            </p>
        </div>
    </x-slot>
    <x-slot name="headerActions">
        @php
            $statusBadge = match($requisition->status) {
                'draft' => ['badge-gray', 'Draft'],
                'pending_quotations' => ['badge-yellow', 'Collecting Quotations'],
                'quotation_selected' => ['badge-indigo', 'Quote Selected'],
                'pending_stage1' => ['badge-yellow', 'Stage 1 Review'],
                'pending_stage2' => ['badge-yellow', 'Stage 2 Review'],
                'po_generated' => ['badge-indigo', 'PO Generated'],
                'invoice_uploaded' => ['badge-indigo', 'Invoice Received'],
                'rejected' => ['badge-red', 'Rejected'],
                'closed' => ['badge-green', 'Closed'],
                default => ['badge-gray', ucfirst($requisition->status)]
            };
        @endphp
        <span class="badge {{ $statusBadge[0] }}" style="font-size:0.8rem;padding:0.35rem 0.85rem">
            {{ $statusBadge[1] }}
        </span>
    </x-slot>

    @if(session('success'))
        <div class="alert alert-success section-gap"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger section-gap"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    <div class="grid" style="grid-template-columns: 1fr 340px; gap: 1.5rem; align-items: start">
        
        <!-- Left Column -->
        <div style="display:flex;flex-direction:column;gap:1.25rem">
            
            <!-- Details Card -->
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-info-circle" style="color:#6b7280;margin-right:0.4rem"></i>Requisition Details</h2>
                </div>
                <div class="card-body">
                    <div class="grid grid-2" style="gap:1rem;margin-bottom:1rem">
                        <div>
                            <div class="form-label" style="color:#6b7280">Department</div>
                            <div style="font-weight:500">{{ $requisition->department->name ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <div class="form-label" style="color:#6b7280">Requested By</div>
                            <div style="font-weight:500">{{ $requisition->user->name }}</div>
                        </div>
                    </div>
                    <div>
                        <div class="form-label" style="color:#6b7280">Description & Justification</div>
                        <p style="margin:0.25rem 0 0;line-height:1.6;color:#374151;white-space:pre-wrap">{{ $requisition->description ?: 'No description provided.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Quotations Card -->
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-file-invoice" style="color:#6b7280;margin-right:0.4rem"></i>
                        Uploaded Quotations
                    </h2>
                    <span class="badge badge-gray">{{ $requisition->quotations->count() }} total</span>
                </div>
                @if($requisition->quotations->count() > 0)
                <div style="padding:1rem 1.5rem;display:flex;flex-direction:column;gap:0.75rem">
                    @foreach($requisition->quotations as $quote)
                    <div style="border:1.5px solid {{ $quote->is_selected ? '#059669' : '#e5e7eb' }};border-radius:10px;padding:1rem 1.25rem;background:{{ $quote->is_selected ? '#f0fdf4' : '#fafafa' }};display:flex;align-items:center;justify-content:space-between;gap:1rem">
                        <div>
                            <div style="font-size:1.3rem;font-weight:800;color:#111827">₹{{ number_format($quote->final_price, 2) }}</div>
                            <div style="font-weight:600;color:#4f46e5;font-size:0.875rem">{{ $quote->vendor->name }}</div>
                            <div style="font-size:0.78rem;color:#6b7280;margin-top:2px">{{ $quote->vendor->email }} &bull; {{ $quote->vendor->phone }}</div>
                        </div>
                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:0.4rem">
                            <a href="{{ asset('storage/' . $quote->file_path) }}" target="_blank" class="btn btn-secondary btn-sm">
                                <i class="fas fa-file-pdf" style="color:#dc2626"></i> View PDF
                            </a>
                            @if($quote->is_system_recommended)
                                <span class="badge badge-indigo"><i class="fas fa-robot"></i> Best Price</span>
                            @endif
                            @if($quote->is_selected)
                                <span class="badge badge-green"><i class="fas fa-check-circle"></i> Selected</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($requisition->status === 'pending_quotations' && !$requisition->selected_quotation_id)
                <div style="padding:1rem 1.5rem;border-top:1px solid #f3f4f6">
                    <p style="font-size:0.875rem;font-weight:600;margin:0 0 0.75rem;color:#111827">Select Winning Quotation</p>
                    <form action="{{ route('quotations.select', $requisition) }}" method="POST" style="display:flex;gap:0.75rem">
                        @csrf
                        <select name="quotation_id" class="form-control" style="flex:1">
                            <option value="">-- Choose winning quotation --</option>
                            @foreach($requisition->quotations as $quote)
                                <option value="{{ $quote->id }}">
                                    {{ $quote->vendor->name }} — ₹{{ number_format($quote->final_price, 2) }} {{ $quote->is_system_recommended ? '(Recommended ★)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary" style="white-space:nowrap">
                            <i class="fas fa-paper-plane"></i> Submit for Approval
                        </button>
                    </form>
                    <p style="font-size:0.75rem;color:#6b7280;margin:0.5rem 0 0">This will send the PR to Stage 1 (Compliance) for approval.</p>
                </div>
                @endif

                @else
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fas fa-file-invoice"></i></div>
                        <p>No quotations uploaded yet. Use the form on the right to add one.</p>
                    </div>
                @endif
            </div>

            @if($requisition->purchaseOrder && in_array($requisition->status, ['po_generated','invoice_uploaded','closed']))
            <!-- PO Download Card -->
            <div class="card" style="border-left:4px solid #4f46e5">
                <div class="card-header" style="background:#eef2ff">
                    <div style="display:flex;align-items:center;gap:0.6rem">
                        <div style="width:32px;height:32px;background:#4f46e5;border-radius:8px;display:flex;align-items:center;justify-content:center;color:white;font-size:14px">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <div>
                            <div style="font-size:0.7rem;font-weight:700;color:#4f46e5;text-transform:uppercase;letter-spacing:0.05em">Purchase Order Generated</div>
                            <div style="font-size:1rem;font-weight:800;color:#111827">{{ $requisition->purchaseOrder->po_number }}</div>
                        </div>
                    </div>
                    <span class="badge badge-green"><i class="fas fa-check-circle"></i> Approved</span>
                </div>
                <div class="card-body">
                    <div class="grid grid-2" style="gap:0.75rem;margin-bottom:1rem">
                        <div>
                            <div style="font-size:0.72rem;color:#6b7280;font-weight:600;text-transform:uppercase">PO Amount</div>
                            <div style="font-size:1.3rem;font-weight:800;color:#059669">₹{{ number_format($requisition->purchaseOrder->total_amount, 2) }}</div>
                        </div>
                        <div>
                            <div style="font-size:0.72rem;color:#6b7280;font-weight:600;text-transform:uppercase">Issue Date</div>
                            <div style="font-size:0.95rem;font-weight:600">{{ $requisition->purchaseOrder->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                    @php $safePoNumber = str_replace('/', '_', $requisition->purchaseOrder->po_number); @endphp
                    @if(file_exists(storage_path('app/public/purchase_orders/' . $safePoNumber . '.pdf')))
                    <a href="{{ asset('storage/purchase_orders/' . $safePoNumber . '.pdf') }}" target="_blank" class="btn btn-primary" style="width:100%;justify-content:center">
                        <i class="fas fa-download"></i> Download Purchase Order PDF
                    </a>
                    @else
                    <p style="font-size:0.8rem;color:#6b7280;margin:0"><i class="fas fa-info-circle"></i> PO PDF is being generated...</p>
                    @endif
                </div>
            </div>
            @endif

        </div>

        <!-- Right Column -->
        <div style="display:flex;flex-direction:column;gap:1.25rem">
            
            @if($requisition->status === 'pending_quotations')
            <!-- Upload Quotation Form -->
            <div class="card">
                <div class="card-header">
                    <h2><i class="fas fa-upload" style="color:#6b7280;margin-right:0.4rem"></i>Add Quotation</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('quotations.store', $requisition) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Vendor Name <span style="color:red">*</span></label>
                            <input type="text" name="vendor_name" class="form-control" placeholder="e.g. Tech Supplies Ltd" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="vendor_email" class="form-control" placeholder="vendor@example.com">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="vendor_phone" class="form-control" placeholder="+1 234 567 8900">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Total Quoted Amount (₹) <span style="color:red">*</span></label>
                            <input type="number" step="0.01" name="final_price" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Quotation PDF <span style="color:red">*</span></label>
                            <div class="file-drop" onclick="document.getElementById('qfile').click()">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <div style="font-size:0.875rem;color:#374151;font-weight:500">Click to upload PDF</div>
                                <div id="qfile-name" style="font-size:0.75rem;color:#6b7280;margin-top:4px">No file chosen</div>
                            </div>
                            <input type="file" id="qfile" name="quotation_pdf" accept="application/pdf" required onchange="document.getElementById('qfile-name').textContent = this.files[0]?.name || 'No file chosen'">
                            @error('quotation_pdf')<p style="color:#dc2626;font-size:0.78rem;margin-top:0.3rem">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                            <i class="fas fa-plus"></i> Upload Quotation
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Status Timeline -->
            <div class="card">
                <div class="card-header"><h2>Workflow Status</h2></div>
                <div class="card-body" style="padding:1rem 1.25rem">
                    @php
                        $stages = [
                            ['label'=>'PR Created','key'=>'any','icon'=>'fas fa-file'],
                            ['label'=>'Quotations','key'=>'pending_quotations','icon'=>'fas fa-file-invoice'],
                            ['label'=>'Stage 1 Approval','key'=>'pending_stage1','icon'=>'fas fa-user-check'],
                            ['label'=>'Stage 2 Approval','key'=>'pending_stage2','icon'=>'fas fa-user-shield'],
                            ['label'=>'PO Generated','key'=>'po_generated','icon'=>'fas fa-file-contract'],
                            ['label'=>'Invoice Upload','key'=>'invoice_uploaded','icon'=>'fas fa-receipt'],
                            ['label'=>'Closed','key'=>'closed','icon'=>'fas fa-check-double'],
                        ];
                        $stageOrder = ['draft','pending_quotations','quotation_selected','pending_stage1','pending_stage2','po_generated','invoice_uploaded','closed'];
                        $currentIdx = array_search($requisition->status, $stageOrder);
                    @endphp
                    @foreach($stages as $i => $stage)
                    @php
                        $stageIdx = $i;
                        if($i >= 1) $stageIdx = $i + ($i >= 2 ? 1 : 0);
                        $isDone = $currentIdx !== false && $currentIdx > $i;
                        $isActive = $currentIdx === $i;
                    @endphp
                    <div style="display:flex;align-items:center;gap:0.75rem;padding:0.5rem 0;{{ !$loop->last ? 'border-bottom:1px dashed #f3f4f6' : '' }}">
                        <div style="width:28px;height:28px;border-radius:50%;background:{{ $isDone ? '#059669' : ($isActive ? '#4f46e5' : '#f3f4f6') }};color:{{ ($isDone || $isActive) ? 'white' : '#9ca3af' }};display:flex;align-items:center;justify-content:center;font-size:11px;flex-shrink:0">
                            <i class="{{ $isDone ? 'fas fa-check' : $stage['icon'] }}"></i>
                        </div>
                        <span style="font-size:0.8rem;font-weight:{{ $isActive ? '600' : '400' }};color:{{ $isDone ? '#059669' : ($isActive ? '#4f46e5' : '#9ca3af') }}">
                            {{ $stage['label'] }}
                        </span>
                        @if($isActive)
                        <span class="badge badge-indigo" style="margin-left:auto;font-size:0.68rem">Now</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
