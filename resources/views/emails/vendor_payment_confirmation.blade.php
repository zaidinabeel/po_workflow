<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payment Confirmation</title>
<style>
  body { margin:0; padding:0; background:#f3f4f6; font-family:'Segoe UI',Arial,sans-serif; }
  .wrapper { max-width:600px; margin:32px auto; }
  .header { background:#059669; border-radius:12px 12px 0 0; padding:28px 32px; text-align:center; }
  .header h1 { color:white; margin:0; font-size:1.3rem; font-weight:700; }
  .header p  { color:rgba(255,255,255,0.75); margin:6px 0 0; font-size:0.85rem; }
  .body   { background:white; padding:32px; }
  .highlight { background:#ecfdf5; border-left:4px solid #059669; border-radius:8px; padding:16px 20px; margin:20px 0; text-align:center; }
  .amount { font-size:2rem; font-weight:800; color:#059669; }
  .detail-row { display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #f3f4f6; font-size:0.875rem; }
  .detail-label { color:#6b7280; font-weight:600; }
  .detail-value { color:#111827; font-weight:500; }
  .badge-green { display:inline-block; background:#d1fae5; color:#065f46; padding:4px 12px; border-radius:20px; font-size:0.8rem; font-weight:700; }
  .footer { background:#f9fafb; border:1px solid #e5e7eb; border-top:none; border-radius:0 0 12px 12px; padding:18px 32px; text-align:center; font-size:0.78rem; color:#9ca3af; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <h1>✅ Payment Confirmation</h1>
    <p>Your invoice has been processed — payment completed</p>
  </div>
  <div class="body">
    @php
      $po     = $invoice->purchaseOrder;
      $vendor = $po->vendor ?? $invoice->vendor;
    @endphp

    <p style="font-size:1rem;color:#111827">Dear <strong>{{ $vendor->name ?? 'Vendor' }}</strong>,</p>
    <p style="color:#374151;font-size:0.9rem;line-height:1.6">
      We are pleased to inform you that the payment for Purchase Order <strong>{{ $po->po_number ?? 'N/A' }}</strong> has been successfully processed. Please acknowledge receipt of this payment.
    </p>

    <div class="highlight">
      <div style="font-size:0.8rem;color:#065f46;margin-bottom:4px;font-weight:600">AMOUNT PAID</div>
      <div class="amount">₹{{ number_format($po->total_amount, 2) }}</div>
      <div style="margin-top:8px"><span class="badge-green">✓ Payment Completed</span></div>
    </div>

    <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:20px;margin:20px 0">
      <div class="detail-row">
        <span class="detail-label">PO Number</span>
        <span class="detail-value" style="font-weight:700">{{ $po->po_number ?? 'N/A' }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">PR / Project</span>
        <span class="detail-value">{{ optional($po->purchaseRequisition)->title ?? 'N/A' }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Payment Date</span>
        <span class="detail-value">{{ now()->format('d M Y') }}</span>
      </div>
      @if($paymentRef)
      <div class="detail-row">
        <span class="detail-label">Payment Ref / UTR</span>
        <span class="detail-value" style="font-weight:700;color:#059669">{{ $paymentRef }}</span>
      </div>
      @endif
      <div class="detail-row" style="border:none">
        <span class="detail-label">Payment Status</span>
        <span class="detail-value"><span class="badge-green">Paid</span></span>
      </div>
    </div>

    <p style="font-size:0.87rem;color:#374151;line-height:1.6">
      Kindly acknowledge receipt of this payment at your earliest convenience. Please quote the PO number <strong>{{ $po->po_number ?? '' }}</strong> in all future correspondence related to this transaction.
    </p>

    <p style="font-size:0.8rem;color:#6b7280">
      For any queries regarding this payment, please contact us at <a href="mailto:adnan.raikar@mainlydigital.in" style="color:#059669">adnan.raikar@mainlydigital.in</a>
    </p>
  </div>
  <div class="footer">
    <strong>The SERVANTS TRUST</strong> &bull; ProcureFlow Payment System<br>
    This is an automated notification. Please do not reply directly to this email.
  </div>
</div>
</body>
</html>
