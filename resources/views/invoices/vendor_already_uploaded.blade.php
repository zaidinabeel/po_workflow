<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Already Submitted — {{ $po->po_number }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f8fafc; margin: 0; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { background: white; border-radius: 16px; border: 1px solid #e5e7eb; padding: 2.5rem; max-width: 440px; text-align: center; }
        .icon { width: 70px; height: 70px; background: #fef3c7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 1.75rem; color: #d97706; }
        h2 { font-size: 1.3rem; font-weight: 800; margin: 0 0 0.5rem; }
        p { color: #6b7280; font-size: 0.875rem; margin: 0 0 0.75rem; line-height: 1.6; }
        .po-ref { background: #eef2ff; border-radius: 8px; padding: 0.5rem 1rem; display: inline-block; font-weight: 700; color: #4f46e5; font-size: 0.9rem; }
        a { color: #4f46e5; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon"><i class="fas fa-clock"></i></div>
        <h2>Invoice Already Submitted</h2>
        <p>An invoice for the following purchase order has already been uploaded:</p>
        <div class="po-ref">{{ $po->po_number }}</div>
        <p style="margin-top:1rem">Our Accounts team is reviewing it. For any queries, please contact <a href="mailto:adnan.raikar@mainlydigital.in">adnan.raikar@mainlydigital.in</a>.</p>
    </div>
</body>
</html>
