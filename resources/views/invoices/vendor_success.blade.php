<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Submitted — {{ $po->po_number }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; margin: 0; display: flex; flex-direction: column; min-height: 100vh; align-items: center; justify-content: center; color: #111827; }
        .header { background: #1e1b4b; width: 100%; padding: 1rem 2rem; display: flex; align-items: center; gap: 0.75rem; position: fixed; top: 0; left: 0; }
        .header h1 { font-size: 1rem; font-weight: 700; color: white; margin: 0; }
        .header p { font-size: 0.75rem; color: rgba(255,255,255,0.7); margin: 0; }
        .success-card { background: white; border-radius: 16px; border: 1px solid #e5e7eb; padding: 2.5rem; max-width: 480px; width: 100%; text-align: center; margin: 5rem auto; }
        .check-circle { width: 70px; height: 70px; background: #d1fae5; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; font-size: 1.75rem; color: #059669; }
        h2 { font-size: 1.4rem; font-weight: 800; margin: 0 0 0.5rem; }
        p { color: #6b7280; font-size: 0.9rem; line-height: 1.6; margin: 0 0 1rem; }
        .po-ref { background: #eef2ff; border-radius: 8px; padding: 0.75rem 1.25rem; display: inline-block; font-weight: 700; color: #4f46e5; font-size: 0.95rem; margin: 0.5rem 0 1rem; }
        .footer-note { font-size: 0.78rem; color: #9ca3af; margin-top: 1.5rem; }
        a { color: #4f46e5; }
    </style>
</head>
<body>
    <div class="header">
        <i class="fas fa-file-invoice-dollar" style="color:white"></i>
        <div>
            <h1>ProcureFlow — Axis Capital Ltd.</h1>
            <p>Vendor Invoice Portal</p>
        </div>
    </div>
    <div class="success-card">
        <div class="check-circle"><i class="fas fa-check"></i></div>
        <h2>Invoice Submitted Successfully!</h2>
        <p>Your invoice for PO:</p>
        <div class="po-ref">{{ $po->po_number }}</div>
        <p>has been received and forwarded to the Accounts team at <strong>Axis Capital Ltd.</strong> for processing.</p>
        <p>You will be contacted once the payment is processed. Please quote <strong>{{ $po->po_number }}</strong> in all future correspondence.</p>
        <div class="footer-note">
            Questions? <a href="mailto:adnan.raikar@mainlydigital.in">adnan.raikar@mainlydigital.in</a>
        </div>
    </div>
</body>
</html>
