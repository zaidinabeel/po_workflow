<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PR Status Update</title>
<style>
  body { margin:0; padding:0; background:#f3f4f6; font-family:'Segoe UI',Arial,sans-serif; }
  .wrapper { max-width:600px; margin:32px auto; }
  .header-approved  { background:#059669; border-radius:12px 12px 0 0; padding:28px 32px; text-align:center; }
  .header-rejected  { background:#dc2626; border-radius:12px 12px 0 0; padding:28px 32px; text-align:center; }
  .header-po        { background:#4f46e5; border-radius:12px 12px 0 0; padding:28px 32px; text-align:center; }
  .header-approved h1, .header-rejected h1, .header-po h1 { color:white; margin:0; font-size:1.3rem; font-weight:700; }
  .header-approved p, .header-rejected p, .header-po p { color:rgba(255,255,255,0.75); margin:6px 0 0; font-size:0.85rem; }
  .body   { background:white; padding:32px; }
  .detail-row { display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #f3f4f6; font-size:0.875rem; }
  .detail-label { color:#6b7280; font-weight:600; }
  .detail-value { color:#111827; font-weight:500; }
  .btn-green  { display:inline-block; background:#059669; color:white!important; text-decoration:none; padding:13px 32px; border-radius:8px; font-weight:700; font-size:0.95rem; margin:24px 0; }
  .btn-indigo { display:inline-block; background:#4f46e5; color:white!important; text-decoration:none; padding:13px 32px; border-radius:8px; font-weight:700; font-size:0.95rem; margin:24px 0; }
  .btn-red    { display:inline-block; background:#dc2626; color:white!important; text-decoration:none; padding:13px 32px; border-radius:8px; font-weight:700; font-size:0.95rem; margin:24px 0; }
  .comment-box { background:#fff7ed; border-left:4px solid #f59e0b; border-radius:8px; padding:14px 18px; margin:18px 0; }
  .footer { background:#f9fafb; border:1px solid #e5e7eb; border-top:none; border-radius:0 0 12px 12px; padding:18px 32px; text-align:center; font-size:0.78rem; color:#9ca3af; }
</style>
</head>
<body>
<div class="wrapper">
  @if($action === 'rejected')
  <div class="header-rejected">
    <h1>❌ Your PR Has Been Rejected</h1>
    <p>Action required — please review the feedback below</p>
  </div>
  @elseif($action === 'po_generated')
  <div class="header-po">
    <h1>🎉 Purchase Order Generated!</h1>
    <p>Your request has been fully approved</p>
  </div>
  @else
  <div class="header-approved">
    <h1>✅ PR Approved — Stage {{ $stageNumber }}</h1>
    <p>Your requisition has passed Stage {{ $stageNumber }} review</p>
  </div>
  @endif

  <div class="body">
    <p style="font-size:1rem;color:#111827">Dear <strong>{{ $requisition->user->name ?? 'Requester' }}</strong>,</p>

    @if($action === 'rejected')
    <p style="color:#374151;font-size:0.9rem;line-height:1.6">
      We regret to inform you that your Purchase Requisition <strong>"{{ $requisition->title }}"</strong> has been <strong style="color:#dc2626">rejected</strong> at Stage {{ $stageNumber }}.
    </p>
    @elseif($action === 'po_generated')
    <p style="color:#374151;font-size:0.9rem;line-height:1.6">
      Great news! Your Purchase Requisition <strong>"{{ $requisition->title }}"</strong> has been <strong style="color:#4f46e5">fully approved</strong> and a Purchase Order has been generated and dispatched to the vendor.
    </p>
    @else
    <p style="color:#374151;font-size:0.9rem;line-height:1.6">
      Your Purchase Requisition <strong>"{{ $requisition->title }}"</strong> has been <strong style="color:#059669">approved</strong> at Stage {{ $stageNumber }}.
      @if($stageNumber === 1) It will now proceed to the final Stage 2 (IT) review. @endif
    </p>
    @endif

    @if($comments)
    <div class="comment-box">
      <div style="font-size:0.78rem;font-weight:700;color:#92400e;margin-bottom:4px">REVIEWER COMMENTS</div>
      <p style="margin:0;color:#78350f;font-size:0.88rem">{{ $comments }}</p>
    </div>
    @endif

    <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:20px;margin:20px 0">
      <div class="detail-row">
        <span class="detail-label">PR Title</span>
        <span class="detail-value">{{ $requisition->title }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">PR Reference</span>
        <span class="detail-value">#{{ $requisition->id }}</span>
      </div>
      @if($requisition->selectedQuotation)
      <div class="detail-row">
        <span class="detail-label">Approved Amount</span>
        <span class="detail-value" style="font-weight:800;color:#059669">₹{{ number_format($requisition->selectedQuotation->final_price, 2) }}</span>
      </div>
      @endif
      <div class="detail-row" style="border:none">
        <span class="detail-label">Current Status</span>
        <span class="detail-value">
          @if($action === 'rejected') ❌ Rejected
          @elseif($action === 'po_generated') 🎉 PO Generated & Dispatched
          @else ✅ Stage {{ $stageNumber }} Approved
          @endif
        </span>
      </div>
    </div>

    <div style="text-align:center">
      @if($action === 'rejected')
      <a href="{{ $prUrl }}" class="btn-red">View Rejected PR</a>
      @elseif($action === 'po_generated')
      <a href="{{ $prUrl }}" class="btn-indigo">Download Purchase Order</a>
      @else
      <a href="{{ $prUrl }}" class="btn-green">View PR Status</a>
      @endif
    </div>

    <p style="font-size:0.82rem;color:#6b7280;margin-top:0;text-align:center">
      For any queries, contact us at <a href="mailto:adnan.raikar@mainlydigital.in" style="color:#4f46e5">adnan.raikar@mainlydigital.in</a>
    </p>
  </div>
  <div class="footer">
    <strong>The SERVANTS TRUST</strong> &bull; ProcureFlow Workflow System<br>
    This is an automated notification. Please do not reply directly to this email.
  </div>
</div>
</body>
</html>
