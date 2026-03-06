<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 style="font-size:1.15rem;font-weight:700;margin:0">Invoice Review — Accounts</h1>
            <p style="font-size:0.8rem;color:#6b7280;margin:2px 0 0">Review vendor invoices and process payments</p>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="alert alert-success section-gap"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    {{-- ── Status Tabs ──────────────────────────────────────────────── --}}
    <div style="display:flex;gap:0.5rem;margin-bottom:1.25rem;border-bottom:2px solid #e5e7eb;padding-bottom:0">
        @foreach([
            ['key'=>'pending','label'=>'Pending Payment','icon'=>'fas fa-clock','count'=>$pendingCount,'color'=>'#d97706'],
            ['key'=>'paid','label'=>'Paid','icon'=>'fas fa-check-double','count'=>$paidCount,'color'=>'#059669'],
            ['key'=>'all','label'=>'All Invoices','icon'=>'fas fa-list','count'=>$totalCount,'color'=>'#4f46e5'],
        ] as $tab)
        <a href="{{ route('invoices.index', ['status'=>$tab['key']]) }}"
           style="display:inline-flex;align-items:center;gap:0.4rem;padding:0.6rem 1.1rem;font-size:0.82rem;font-weight:600;border-radius:8px 8px 0 0;text-decoration:none;
                  {{ $filter === $tab['key'] ? 'background:white;color:'.$tab['color'].';border:2px solid #e5e7eb;border-bottom:2px solid white;margin-bottom:-2px' : 'color:#6b7280;border:2px solid transparent' }}">
            <i class="{{ $tab['icon'] }}"></i>
            {{ $tab['label'] }}
            <span style="background:{{ $filter===$tab['key'] ? $tab['color'] : '#e5e7eb' }};color:{{ $filter===$tab['key'] ? 'white' : '#374151' }};padding:1px 7px;border-radius:20px;font-size:0.72rem;font-weight:700">
                {{ $tab['count'] }}
            </span>
        </a>
        @endforeach
    </div>

    {{-- ── Mark as Paid Modal ───────────────────────────────────────── --}}
    <div id="payModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:9999;align-items:center;justify-content:center">
        <div style="background:white;border-radius:16px;width:100%;max-width:480px;margin:0 1rem;box-shadow:0 20px 60px rgba(0,0,0,0.3);overflow:hidden">
            <div style="background:#059669;padding:20px 24px">
                <h2 style="margin:0;color:white;font-size:1.1rem"><i class="fas fa-check-double"></i> Confirm Payment</h2>
                <p style="margin:4px 0 0;color:rgba(255,255,255,0.8);font-size:0.8rem">This will mark the invoice as Paid and email the vendor a payment receipt.</p>
            </div>
            <form id="payForm" method="POST" action="">
                @csrf
                <div style="padding:24px">
                    <div id="payModalMeta" style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:12px 16px;margin-bottom:16px;font-size:0.85rem;color:#374151"></div>
                    <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:6px">Payment Reference / UTR / Cheque No. <span style="color:#9ca3af;font-weight:400">(optional)</span></label>
                    <input type="text" name="notes" id="payRefInput" class="form-control" placeholder="e.g. UTR123456 or Cheque No. 0042">
                </div>
                <div style="display:flex;justify-content:flex-end;gap:0.75rem;padding:0 24px 20px">
                    <button type="button" onclick="closePayModal()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-check-double"></i> Confirm & Mark Paid</button>
                </div>
            </form>
        </div>
    </div>
    <script>
    function openPayModal(invoiceId, label, amount) {
        document.getElementById('payForm').action = '/invoices/' + invoiceId + '/pay';
        document.getElementById('payRefInput').value = '';
        document.getElementById('payModalMeta').innerHTML =
            '<strong>' + label + '</strong><br><span style="font-size:1.1rem;font-weight:800;color:#059669">\u20b9' + amount + '</span>';
        document.getElementById('payModal').style.display = 'flex';
    }
    function closePayModal() { document.getElementById('payModal').style.display = 'none'; }
    document.getElementById('payModal').addEventListener('click', function(e) { if(e.target===this) closePayModal(); });
    </script>

    {{-- ── Invoice Cards ────────────────────────────────────────────── --}}
    @if($invoices->count() > 0)
        <div style="display:flex;flex-direction:column;gap:1.25rem">
            @foreach($invoices as $invoice)
            @php
                $isPaid = $invoice->status === 'paid';
                $vendor = $invoice->vendor ?? $invoice->purchaseOrder?->vendor;
                $po     = $invoice->purchaseOrder;
                $pr     = $po?->purchaseRequisition;
                $safePoNumber = $po ? str_replace('/', '_', $po->po_number) : '';
            @endphp
            <div class="card" style="{{ $isPaid ? 'opacity:0.85' : '' }}">
                {{-- ── Card Header ── --}}
                <div class="card-header" style="{{ $isPaid ? 'background:#f0fdf4' : 'background:white' }}">
                    <div>
                        <div style="display:flex;align-items:center;gap:0.6rem;margin-bottom:3px">
                            <h2 style="margin:0;font-size:1rem">{{ $po?->po_number ?? 'N/A' }}</h2>
                            @if($isPaid)
                            <span class="badge badge-green"><i class="fas fa-check-circle"></i> Paid</span>
                            @else
                            <span class="badge badge-yellow"><i class="fas fa-clock"></i> Awaiting Payment</span>
                            @endif
                        </div>
                        <div style="font-size:0.78rem;color:#6b7280">
                            <strong style="color:#374151">{{ $pr?->title ?? 'N/A' }}</strong>
                            &bull; Uploaded {{ $invoice->created_at->format('d M Y, h:i A') }}
                            @if($isPaid && $invoice->paid_at)
                            &bull; Paid on {{ \Carbon\Carbon::parse($invoice->paid_at)->format('d M Y, h:i A') }}
                            @endif
                        </div>
                    </div>
                    <div style="text-align:right">
                        <div style="font-size:1.5rem;font-weight:800;color:{{ $isPaid ? '#059669' : '#111827' }}">₹{{ number_format($po?->total_amount, 2) }}</div>
                        <div style="font-size:0.75rem;color:#9ca3af">PO Value</div>
                    </div>
                </div>

                {{-- ── Vendor Details Panel ── --}}
                <div style="padding:1rem 1.5rem;border-bottom:1px solid #f3f4f6;background:#fafafa">
                    <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#9ca3af;margin-bottom:0.6rem">Vendor Information</div>
                    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:0.75rem">
                        <div>
                            <div style="font-size:0.7rem;color:#9ca3af;font-weight:600">Vendor Name</div>
                            <div style="font-size:0.875rem;font-weight:700;color:#111827">{{ $vendor?->name ?? '—' }}</div>
                        </div>
                        <div>
                            <div style="font-size:0.7rem;color:#9ca3af;font-weight:600">Email</div>
                            <div style="font-size:0.875rem;color:#4f46e5">
                                @if($vendor?->email)
                                <a href="mailto:{{ $vendor->email }}" style="color:#4f46e5;text-decoration:none">{{ $vendor->email }}</a>
                                @else —
                                @endif
                            </div>
                        </div>
                        <div>
                            <div style="font-size:0.7rem;color:#9ca3af;font-weight:600">Phone</div>
                            <div style="font-size:0.875rem;color:#374151">{{ $vendor?->phone ?? '—' }}</div>
                        </div>
                        <div>
                            <div style="font-size:0.7rem;color:#9ca3af;font-weight:600">Vendor Notes / Invoice Ref</div>
                            <div style="font-size:0.8rem;color:#374151">{{ $invoice->notes ?: '—' }}</div>
                        </div>
                    </div>
                </div>

                {{-- ── Actions Footer ── --}}
                <div style="padding:0.9rem 1.5rem;background:#f9fafb;border-radius:0 0 12px 12px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem">
                    <div style="display:flex;align-items:center;gap:0.6rem">
                        {{-- View Invoice PDF --}}
                        <a href="{{ asset('storage/' . $invoice->file_path) }}" target="_blank" class="btn btn-secondary btn-sm">
                            <i class="fas fa-file-pdf" style="color:#dc2626"></i> View Invoice PDF
                        </a>
                        {{-- View PO PDF --}}
                        @if($po && file_exists(storage_path('app/public/purchase_orders/' . $safePoNumber . '.pdf')))
                        <a href="{{ asset('storage/purchase_orders/' . $safePoNumber . '.pdf') }}" target="_blank" class="btn btn-secondary btn-sm">
                            <i class="fas fa-file-contract" style="color:#4f46e5"></i> View PO PDF
                        </a>
                        @endif
                    </div>

                    <div style="display:flex;align-items:center;gap:0.6rem">
                        @if(!$isPaid)
                        {{-- Mark as Paid — opens modal --}}
                        <button type="button" class="btn btn-success"
                            onclick="openPayModal(
                                '{{ $invoice->id }}',
                                '{{ addslashes($po?->po_number ?? 'N/A') }}',
                                '{{ number_format($po?->total_amount, 2) }}'
                            )">
                            <i class="fas fa-check-double"></i> Mark as Paid
                        </button>
                        @else
                        {{-- Payment reference if stored --}}
                        <div style="font-size:0.8rem;color:#6b7280">
                            @if($invoice->notes)<i class="fas fa-receipt" style="color:#059669"></i> Ref: {{ $invoice->notes }}@endif
                        </div>
                        {{-- Mark as Pending (undo) --}}
                        <form action="{{ route('invoices.pending', $invoice) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm" style="color:#d97706" onclick="return confirm('Move this invoice back to Pending?')">
                                <i class="fas fa-undo"></i> Mark as Pending
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="card">
            <div class="empty-state">
                <div class="empty-icon" style="background:#d1fae5;color:#059669"><i class="fas fa-check-double"></i></div>
                <h3>
                    @if($filter === 'pending') No Pending Invoices
                    @elseif($filter === 'paid') No Paid Invoices Yet
                    @else No Invoices Found
                    @endif
                </h3>
                <p>
                    @if($filter === 'pending') All invoices have been processed — nothing awaiting payment.
                    @elseif($filter === 'paid') Invoices will appear here once payment is marked.
                    @else No invoices have been uploaded by vendors yet.
                    @endif
                </p>
            </div>
        </div>
    @endif
</x-app-layout>
