<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'ProcureFlow') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            * { box-sizing: border-box; }
            body { font-family: 'Inter', sans-serif; margin: 0; background: #f8fafc; min-height: 100vh; display: flex; }
            .auth-split { display: flex; min-height: 100vh; width: 100%; }
            .auth-left {
                width: 420px; flex-shrink: 0; background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
                display: flex; flex-direction: column; justify-content: center; padding: 3rem;
                position: relative; overflow: hidden;
            }
            .auth-left::before {
                content: ''; position: absolute; top: -60px; right: -60px;
                width: 280px; height: 280px; background: rgba(255,255,255,0.07); border-radius: 50%;
            }
            .auth-left::after {
                content: ''; position: absolute; bottom: -80px; left: -40px;
                width: 320px; height: 320px; background: rgba(255,255,255,0.05); border-radius: 50%;
            }
            .auth-brand { position: relative; z-index: 1; }
            .auth-brand .logo-row { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2rem; }
            .auth-brand .logo-icon { width: 42px; height: 42px; background: rgba(255,255,255,0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; }
            .auth-brand .logo-icon i { color: white; font-size: 18px; }
            .auth-brand .logo-name { font-size: 1.25rem; font-weight: 700; color: white; }
            .auth-brand h1 { font-size: 2rem; font-weight: 800; color: white; margin: 0 0 0.75rem; line-height: 1.2; }
            .auth-brand p { color: rgba(255,255,255,0.7); line-height: 1.6; font-size: 0.9rem; margin: 0 0 2rem; }
            .auth-features { display: flex; flex-direction: column; gap: 0.75rem; }
            .auth-feature { display: flex; align-items: center; gap: 0.6rem; color: rgba(255,255,255,0.85); font-size: 0.875rem; }
            .auth-feature i { color: #a5f3fc; }
            .auth-right {
                flex: 1; display: flex; align-items: center; justify-content: center;
                padding: 2rem; background: #f8fafc;
            }
            .auth-card { background: white; border-radius: 16px; padding: 2.5rem; width: 100%; max-width: 440px; box-shadow: 0 4px 24px rgba(0,0,0,0.06); border: 1px solid #e5e7eb; }
            .auth-card h2 { font-size: 1.5rem; font-weight: 800; color: #111827; margin: 0 0 0.4rem; }
            .auth-card p { color: #6b7280; font-size: 0.875rem; margin: 0 0 1.75rem; }
            .form-group { margin-bottom: 1.1rem; }
            .form-label { display: block; font-size: 0.8rem; font-weight: 600; color: #374151; margin-bottom: 0.35rem; }
            .form-control { width: 100%; padding: 0.65rem 0.9rem; border: 1.5px solid #e5e7eb; border-radius: 8px; font-size: 0.875rem; font-family: 'Inter', sans-serif; transition: border-color 0.15s, box-shadow 0.15s; background: #fff; color: #111827; }
            .form-control:focus { outline: none; border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,0.1); }
            .btn-login { width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.7rem 1rem; background: #4f46e5; color: white; border: none; border-radius: 8px; font-size: 0.9rem; font-weight: 700; cursor: pointer; transition: all 0.15s; margin-top: 1.25rem; font-family: 'Inter', sans-serif; }
            .btn-login:hover { background: #3730a3; box-shadow: 0 4px 12px rgba(79,70,229,0.3); }
            .form-check { display: flex; align-items: center; gap: 0.5rem; margin: 0.75rem 0; }
            .form-check input { width: 15px; height: 15px; cursor: pointer; accent-color: #4f46e5; }
            .form-check label { font-size: 0.825rem; color: #6b7280; cursor: pointer; }
            .forgot-link { font-size: 0.8rem; color: #4f46e5; text-decoration: none; }
            .forgot-link:hover { text-decoration: underline; }
            .form-error { color: #dc2626; font-size: 0.78rem; margin-top: 0.3rem; }
            @media (max-width: 768px) { .auth-left { display: none; } .auth-right { padding: 1.5rem; } .auth-card { padding: 1.75rem; } }
        </style>
    </head>
    <body>
        <div class="auth-split">
            <div class="auth-left">
                <div class="auth-brand">
                    <div class="logo-row">
                        <div class="logo-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                        <span class="logo-name">ProcureFlow</span>
                    </div>
                    <h1>Streamline Your Procurement</h1>
                    <p>End-to-end procure-to-pay workflow with multi-stage approvals, automated PO generation, and vendor invoice management.</p>
                    <div class="auth-features">
                        <div class="auth-feature"><i class="fas fa-check-circle"></i> Multi-stage approval workflows</div>
                        <div class="auth-feature"><i class="fas fa-check-circle"></i> Automated quotation comparison</div>
                        <div class="auth-feature"><i class="fas fa-check-circle"></i> PDF purchase order generation</div>
                        <div class="auth-feature"><i class="fas fa-check-circle"></i> Secure vendor invoice upload</div>
                    </div>
                </div>
            </div>
            <div class="auth-right">
                <div class="auth-card">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
