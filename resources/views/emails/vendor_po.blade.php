<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $po->po_number }} - Purchase Order</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; color: #222; margin: 0; padding: 0; background: #f4f6f9; }
        .wrapper { max-width: 600px; margin: 30px auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        .email-header { background: #4f46e5; padding: 28px 36px; }
        .email-header h1 { color: white; margin: 0; font-size: 22px; }
        .email-header p { color: rgba(255,255,255,0.8); margin: 4px 0 0; font-size: 13px; }
        .email-body { padding: 32px 36px; }
        .greeting { font-size: 15px; color: #111; margin-bottom: 20px; }
        .email-body p { line-height: 1.7; color: #374151; margin-bottom: 14px; }
        .po-box { background: #f0f4ff; border-left: 4px solid #4f46e5; padding: 16px 20px; border-radius: 6px; margin: 20px 0; }
        .po-box .label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: #6b7280; }
        .po-box .value { font-size: 1.2rem; font-weight: 800; color: #4f46e5; margin-top: 3px; }
        .upload-btn { display: inline-block; margin: 6px 0 20px; padding: 13px 28px; background: #4f46e5; color: white; text-decoration: none; border-radius: 8px; font-weight: 700; font-size: 14px; }
        .upload-btn:hover { background: #3730a3; }
        .note { background: #fffbeb; border-left: 4px solid #f59e0b; padding: 12px 16px; border-radius: 5px; font-size: 13px; color: #78350f; margin-bottom: 20px; }
        .email-footer { padding: 20px 36px; border-top: 1px solid #e5e7eb; background: #f9fafb; }
        .email-footer p { margin: 0; font-size: 13px; color: #6b7280; }
        .company-name { font-weight: 800; color: #111827; }
        .divider { height: 1px; background: #e5e7eb; margin: 16px 0; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="email-header">
            <h1>Purchase Order — Axis Capital Ltd.</h1>
            <p>Procure-to-Pay System | Axis Capital</p>
        </div>
        <div class="email-body">
            <p class="greeting">Dear <strong>{{ $po->vendor->name }}</strong>,</p>

            <p>Please find attached our purchase order <strong>{{ $po->po_number }}</strong> issued in accordance with our mutually agreed terms.</p>

            <div class="po-box">
                <div class="label">Purchase Order Number</div>
                <div class="value">{{ $po->po_number }}</div>
            </div>

            <p>Kindly acknowledge the receipt of this Purchase Order. Request you to <strong>quote the PO number in all future communication</strong> and on your invoice.</p>

            <div class="note">
                📎 The Purchase Order PDF is attached to this email for your reference.
            </div>

            <p>Additionally, we request you to please <strong>upload your invoice</strong> using the secure link below:</p>

            <a href="{{ $uploadUrl }}" class="upload-btn">
                📤 Upload Your Invoice →
            </a>

            <p style="font-size:12px;color:#9ca3af">If the button above doesn't work, copy and paste this link into your browser:<br>
            <a href="{{ $uploadUrl }}" style="color:#4f46e5;word-break:break-all">{{ $uploadUrl }}</a></p>

            <div class="divider"></div>

            <p>Please feel free to reach us out on <a href="mailto:adnan.raikar@mainlydigital.in" style="color:#4f46e5">adnan.raikar@mainlydigital.in</a> in case of any queries.</p>

            <p>Regards,<br>
            <span class="company-name">Axis Capital Ltd.</span></p>
        </div>
        <div class="email-footer">
            <p>This is an automated email from the ProcureFlow P2P System. Please do not reply to this email directly.</p>
        </div>
    </div>
</body>
</html>
