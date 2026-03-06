<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Action Required — PR Approval</title>
<style>
  body { margin:0; padding:0; background:#f3f4f6; font-family:'Segoe UI',Arial,sans-serif; }
  .wrapper { max-width:600px; margin:32px auto; }
  .header { background:#4f46e5; border-radius:12px 12px 0 0; padding:28px 32px; text-align:center; }
  .header h1 { color:white; margin:0; font-size:1.3rem; font-weight:700; }
  .header p  { color:#c7d2fe; margin:6px 0 0; font-size:0.85rem; }
  .body   { background:white; padding:32px; }
  .alert  { background:#fffbeb; border-left:4px solid #f59e0b; border-radius:8px; padding:16px 20px; margin:20px 0; }
  .alert p { margin:0; color:#92400e; font-size:0.88rem; }
  .detail-row { display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #f3f4f6; font-size:0.875rem; }
  .detail-label { color:#6b7280; font-weight:600; }
  .detail-value { color:#111827; font-weight:500; }
  .btn { display:inline-block; background:#4f46e5; color:white!important; text-decoration:none; padding:13px 32px; border-radius:8px; font-weight:700; font-size:0.95rem; margin:24px 0; }
  .footer { background:#f9fafb; border:1px solid #e5e7eb; border-top:none; border-radius:0 0 12px 12px; padding:18px 32px; text-align:center; font-size:0.78rem; color:#9ca3af; }
  .badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:0.75rem; font-weight:700; }
  .badge-orange { background:#fef3c7; color:#92400e; }
  .badge-purple { background:#ede9fe; color:#4f46e5; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <h1>🔔 Action Required — Approval Needed</h1>
    <p>Purchase Requisition awaiting your decision</p>
  </div>
  <div class="body">
    <p style="font-size:1rem;color:#111827">Dear <strong>{{ $approverName }}</strong>,</p>
    <p style="color:#374151;font-size:0.9rem;line-height:1.6">
      A Purchase Requisition requires your approval. Please review the details below and take action at your earliest convenience.
    </p>

    <div class="alert">
      <p><strong>⚡ Stage:</strong>
        <span class="badge {{ $stage === 'stage1' ? 'badge-orange' : 'badge-purple' }}">
          {{ $stage === 'stage1' ? 'Stage 1 — Compliance Review' : 'Stage 2 — IT Final Approval' }}
        </span>
      </p>
    </div>

    <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:20px;margin:20px 0">
      <div class="detail-row">
        <span class="detail-label">PR Title</span>
        <span class="detail-value">{{ $requisition->title }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Submitted By</span>
        <span class="detail-value">{{ $requisition->user->name ?? 'N/A' }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Department</span>
        <span class="detail-value">{{ $requisition->department->name ?? 'N/A' }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">PR Description</span>
        <span class="detail-value">{{ Str::limit($requisition->description, 100) ?: 'N/A' }}</span>
      </div>
      @if($requisition->selectedQuotation)
      <div class="detail-row">
        <span class="detail-label">Quoted Amount</span>
        <span class="detail-value" style="font-weight:800;color:#059669;font-size:1rem">₹{{ number_format($requisition->selectedQuotation->final_price, 2) }}</span>
      </div>
      <div class="detail-row" style="border:none">
        <span class="detail-label">Vendor</span>
        <span class="detail-value">{{ $requisition->selectedQuotation->vendor->name ?? 'N/A' }}</span>
      </div>
      @endif
    </div>

    <div style="text-align:center">
      <a href="{{ $approvalUrl }}" class="btn">🔍 Review &amp; Approve / Reject</a>
    </div>

    <p style="font-size:0.82rem;color:#6b7280;margin-top:0">
      Please log in to ProcureFlow to take action. If you have any questions, reply to this email.
    </p>
  </div>
  <div class="footer">
    <strong>The SERVANTS TRUST</strong> &bull; ProcureFlow Workflow System<br>
    This is an automated notification. Please do not reply directly to this email.
  </div>
</div>
</body>
</html>
