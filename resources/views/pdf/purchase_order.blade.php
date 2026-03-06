<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Order {{ $po->po_number }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #1a1a1a; padding: 30px; background: white; }
        
        /* ── HEADER ── */
        .header { display: table; width: 100%; border-bottom: 3px solid #4f46e5; padding-bottom: 18px; margin-bottom: 24px; }
        .header-left { display: table-cell; width: 60%; vertical-align: top; }
        .header-right { display: table-cell; width: 40%; text-align: right; vertical-align: top; }
        .company-name { font-size: 22px; font-weight: 900; color: #1e1b4b; letter-spacing: -0.5px; }
        .company-tagline { font-size: 10px; color: #6b7280; margin-top: 2px; }
        .company-address { margin-top: 8px; font-size: 10px; color: #6b7280; line-height: 1.5; }
        .po-title { font-size: 24px; font-weight: 900; color: #4f46e5; text-transform: uppercase; letter-spacing: 1px; }
        .po-number-box { margin-top: 8px; background: #1e1b4b; color: white; padding: 6px 14px; border-radius: 4px; display: inline-block; font-size: 13px; font-weight: 700; }
        .po-meta { margin-top: 8px; font-size: 11px; color: #4b5563; line-height: 1.6; }

        /* ── VENDOR / BILL-TO ── */
        .info-row { display: table; width: 100%; margin-bottom: 22px; }
        .info-col { display: table-cell; width: 50%; vertical-align: top; padding-right: 20px; }
        .info-col:last-child { padding-right: 0; }
        .section-heading { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; margin-bottom: 8px; }
        .info-name { font-size: 13px; font-weight: 700; color: #1a1a1a; }
        .info-detail { font-size: 11px; color: #4b5563; line-height: 1.6; margin-top: 3px; }

        /* ── ITEMS TABLE ── */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table thead tr { background: #1e1b4b; }
        .items-table thead th { color: white; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; padding: 10px 14px; text-align: left; }
        .items-table thead th:last-child { text-align: right; }
        .items-table tbody td { padding: 12px 14px; border-bottom: 1px solid #f3f4f6; font-size: 11px; color: #374151; }
        .items-table tfoot td { padding: 12px 14px; font-weight: 700; font-size: 13px; }
        .items-table tfoot tr { background: #eef2ff; }
        .text-right { text-align: right; }

        /* ── TERMS ── */
        .terms { background: #f9fafb; border-left: 4px solid #4f46e5; padding: 14px 16px; margin-bottom: 22px; border-radius: 0 6px 6px 0; }
        .terms p { font-size: 11px; color: #4b5563; line-height: 1.6; }
        .terms strong { color: #1a1a1a; }

        /* ── FOOTER ── */
        .footer { border-top: 1px solid #e5e7eb; padding-top: 14px; text-align: center; }
        .footer p { font-size: 10px; color: #9ca3af; }
        .footer strong { color: #4f46e5; }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-left">
            <div class="company-name">Axis Capital Ltd.</div>
            <div class="company-tagline">Financial Services | Procurement Division</div>
            <div class="company-address">
                Email: adnan.raikar@mainlydigital.in<br>
                Website: www.mainlydigital.in
            </div>
        </div>
        <div class="header-right">
            <div class="po-title">Purchase Order</div>
            <div class="po-number-box">{{ $po->po_number }}</div>
            <div class="po-meta">
                <strong>Date:</strong> {{ $po->created_at->format('d M Y') }}<br>
                <strong>PR Ref:</strong> {{ $po->purchaseRequisition->title ?? 'N/A' }}<br>
                <strong>Status:</strong> Approved
            </div>
        </div>
    </div>

    <div class="info-row">
        <div class="info-col">
            <div class="section-heading">Vendor / Supplier</div>
            <div class="info-name">{{ $po->vendor->name }}</div>
            <div class="info-detail">
                @if($po->vendor->email) Email: {{ $po->vendor->email }}<br>@endif
                @if($po->vendor->phone) Phone: {{ $po->vendor->phone }}<br>@endif
            </div>
        </div>
        <div class="info-col">
            <div class="section-heading">Bill To / Ship To</div>
            <div class="info-name">Axis Capital Ltd.</div>
            <div class="info-detail">
                Procurement Department<br>
                Attn: Accounts Payable Team<br>
                adnan.raikar@mainlydigital.in
            </div>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Description of Goods / Services</th>
                <th class="text-right">Amount (INR)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>{{ $po->purchaseRequisition->title ?? 'Goods / Services as per approved quotation' }}
                    @if($po->purchaseRequisition->description)
                    <br><span style="color:#9ca3af;font-size:10px">{{ Str::limit($po->purchaseRequisition->description, 120) }}</span>
                    @endif
                </td>
                <td class="text-right">{{ number_format($po->total_amount, 2) }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="text-right">Grand Total:</td>
                <td class="text-right">{{ number_format($po->total_amount, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="terms">
        <p><strong>Terms &amp; Instructions:</strong><br>
        Please supply the goods/services as listed above in accordance with your submitted quotation (Ref: {{ $po->po_number }}).
        Kindly quote the PO number <strong>{{ $po->po_number }}</strong> in all communications and on your invoice.
        Submit your invoice via the secure link sent to your email address or contact <strong>adnan.raikar@mainlydigital.in</strong> for any queries.</p>
    </div>

    <div class="footer">
        <p>This is a computer-generated document issued by <strong>Axis Capital Ltd.</strong> | No physical signature required.</p>
    </div>

</body>
</html>
