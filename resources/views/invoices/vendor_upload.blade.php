<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Invoice — {{ $po->po_number }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; margin: 0; padding: 0; color: #111827; }
        .header { background: #1e1b4b; color: white; padding: 1rem 2rem; display: flex; align-items: center; gap: 1rem; }
        .header .logo-icon { width: 36px; height: 36px; background: rgba(255,255,255,0.15); border-radius: 8px; display: flex; align-items: center; justify-content: center; }
        .header h1 { font-size: 1rem; font-weight: 700; margin: 0; }
        .header p { font-size: 0.75rem; opacity: 0.7; margin: 0; }
        .container { max-width: 860px; margin: 2rem auto; padding: 0 1rem; }
        .card { background: white; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; margin-bottom: 1.5rem; }
        .card-header { padding: 1rem 1.5rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between; }
        .card-header h2 { font-size: 0.95rem; font-weight: 700; margin: 0; }
        .card-body { padding: 1.5rem; }
        
        /* PO Preview */
        .po-preview { background: #f0f4ff; border: 2px solid #c7d2fe; border-radius: 10px; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; }
        .po-number-display { font-size: 1.5rem; font-weight: 800; color: #4f46e5; margin-bottom: 0.25rem; }
        .po-badge { display: inline-flex; align-items: center; gap: 0.3rem; background: #d1fae5; color: #065f46; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.72rem; font-weight: 600; margin-bottom: 0.75rem; }
        .po-details { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-top: 1rem; }
        .po-detail-item .label { font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #9ca3af; }
        .po-detail-item .value { font-size: 0.9rem; font-weight: 600; color: #111827; margin-top: 2px; }
        .view-pdf-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 1rem; background: #4f46e5; color: white; text-decoration: none; border-radius: 7px; font-size: 0.8rem; font-weight: 600; margin-top: 1rem; }
        
        /* Form */
        .form-group { margin-bottom: 1.1rem; }
        .form-label { display: block; font-size: 0.8rem; font-weight: 600; color: #374151; margin-bottom: 0.35rem; }
        .form-label span { color: #dc2626; }
        .form-control { width: 100%; padding: 0.65rem 0.9rem; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: 0.875rem; font-family: 'Inter', sans-serif; transition: border-color 0.15s, box-shadow 0.15s; }
        .form-control:focus { outline: none; border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,0.1); }
        textarea.form-control { min-height: 80px; resize: vertical; }
        .file-drop { border: 2px dashed #d1d5db; border-radius: 10px; padding: 2rem; text-align: center; cursor: pointer; transition: all 0.2s; }
        .file-drop:hover { border-color: #4f46e5; background: #eef2ff; }
        .file-drop i { font-size: 2.5rem; color: #a5b4fc; display: block; margin-bottom: 0.5rem; }
        .file-drop p { font-size: 0.875rem; color: #374151; font-weight: 600; margin: 0; }
        .file-drop small { color: #6b7280; font-size: 0.78rem; }
        #file-name { font-size: 0.78rem; color: #4f46e5; margin-top: 0.5rem; font-weight: 500; }
        .btn-submit { width: 100%; padding: 0.8rem; background: #4f46e5; color: white; border: none; border-radius: 8px; font-size: 0.95rem; font-weight: 700; cursor: pointer; font-family: 'Inter', sans-serif; transition: all 0.15s; margin-top: 0.5rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; }
        .btn-submit:hover { background: #3730a3; box-shadow: 0 4px 12px rgba(79,70,229,0.35); }
        .notice { background: #fffbeb; border-left: 4px solid #f59e0b; padding: 0.85rem 1rem; border-radius: 6px; font-size: 0.825rem; color: #78350f; margin-bottom: 1rem; }
        .alert-error { background: #fef2f2; border-left: 4px solid #dc2626; padding: 0.85rem 1rem; border-radius: 6px; color: #991b1b; font-size: 0.825rem; margin-bottom: 1rem; }
        .footer { text-align: center; padding: 2rem; font-size: 0.78rem; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-icon"><i class="fas fa-file-invoice-dollar" style="color:white"></i></div>
        <div>
            <h1>ProcureFlow — Vendor Invoice Upload</h1>
            <p>Axis Capital Ltd. Procurement System</p>
        </div>
    </div>

    <div class="container">
        @if(session('error'))
            <div class="alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif

        <!-- PO Summary -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-file-contract" style="color:#4f46e5;margin-right:0.4rem"></i> Your Purchase Order</h2>
                <span class="po-badge"><i class="fas fa-check-circle"></i> Approved</span>
            </div>
            <div class="card-body">
                <div class="po-preview">
                    <div class="po-number-display">{{ $po->po_number }}</div>
                    <div class="po-badge"><i class="fas fa-check-circle"></i> Approved &amp; Active</div>
                    <div class="po-details">
                        <div class="po-detail-item">
                            <div class="label">Vendor</div>
                            <div class="value">{{ $po->vendor->name }}</div>
                        </div>
                        <div class="po-detail-item">
                            <div class="label">PO Amount</div>
                            <div class="value" style="color:#059669">₹{{ number_format($po->total_amount, 2) }}</div>
                        </div>
                        <div class="po-detail-item">
                            <div class="label">Issue Date</div>
                            <div class="value">{{ $po->created_at->format('d M Y') }}</div>
                        </div>
                        <div class="po-detail-item" style="grid-column:span 3">
                            <div class="label">Description</div>
                            <div class="value" style="font-weight:500;font-size:0.85rem">{{ $po->purchaseRequisition->title ?? 'Purchase Order' }}</div>
                        </div>
                    </div>
                    @php $safePoNumber = str_replace('/', '_', $po->po_number); @endphp
                    @if(file_exists(storage_path('app/public/purchase_orders/' . $safePoNumber . '.pdf')))
                    <a href="{{ asset('storage/purchase_orders/' . $safePoNumber . '.pdf') }}" target="_blank" class="view-pdf-btn">
                        <i class="fas fa-file-pdf"></i> View / Download PO PDF
                    </a>
                    @endif
                </div>

                <div class="notice">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Important:</strong> Please quote the PO number <strong>{{ $po->po_number }}</strong> on your invoice and in all future communications with us.
                </div>
            </div>
        </div>

        <!-- Upload Form -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-upload" style="color:#4f46e5;margin-right:0.4rem"></i> Upload Your Invoice</h2>
                <span style="font-size:0.78rem;color:#6b7280">One-time submission</span>
            </div>
            <div class="card-body">
                <form action="{{ route('invoices.vendor.store', $po->upload_token) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if($errors->any())
                        <div class="alert-error">
                            <ul style="margin:0;padding-left:1.2rem">
                                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group">
                        <label class="form-label">Invoice PDF <span>*</span></label>
                        <div class="file-drop" onclick="document.getElementById('invoice_pdf').click()">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Click to upload your invoice PDF</p>
                            <small>PDF format only, max 10MB</small>
                            <div id="file-name">No file chosen</div>
                        </div>
                        <input type="file" id="invoice_pdf" name="invoice_pdf" accept="application/pdf" style="display:none" required
                               onchange="document.getElementById('file-name').textContent = this.files[0]?.name || 'No file chosen'">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Notes / Reference (Optional)</label>
                        <textarea name="notes" class="form-control" placeholder="Your invoice number, payment terms, or any other reference..."></textarea>
                    </div>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i> Submit Invoice to Axis Capital Ltd.
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Questions? Contact <a href="mailto:adnan.raikar@mainlydigital.in" style="color:#4f46e5">adnan.raikar@mainlydigital.in</a></p>
        <p style="margin-top:0.5rem">© Axis Capital Ltd. — ProcureFlow System</p>
    </div>
</body>
</html>
