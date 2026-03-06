<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'ProcureFlow') }} — P2P Workflow</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            :root {
                --primary: #4f46e5; --primary-dp: #3730a3; --primary-lt: #eef2ff;
                --success: #059669; --danger: #dc2626; --warn: #d97706;
                --text: #111827; --text-muted: #6b7280; --border: #e5e7eb;
                --bg: #f8fafc; --card: #ffffff; --sidebar-w: 240px;
            }
            * { box-sizing: border-box; }
            body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); margin: 0; }
            .sidebar {
                position: fixed; top: 0; left: 0; height: 100vh; width: var(--sidebar-w);
                background: #fff; border-right: 1px solid var(--border);
                display: flex; flex-direction: column; z-index: 100;
                box-shadow: 2px 0 8px rgba(0,0,0,0.03);
            }
            .sidebar-logo { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 0.65rem; }
            .logo-icon { width: 34px; height: 34px; background: var(--primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
            .logo-icon i { color: white; font-size: 14px; }
            .logo-text span { font-size: 1rem; font-weight: 700; color: var(--text); letter-spacing: -0.01em; display: block; }
            .logo-text small { font-size: 0.68rem; color: var(--text-muted); font-weight: 400; }
            .sidebar-nav { flex: 1; padding: 1rem 0.75rem; overflow-y: auto; }
            .nav-section-label { font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); padding: 0.5rem 0.75rem 0.35rem; }
            .nav-link { display: flex; align-items: center; gap: 0.65rem; padding: 0.55rem 0.75rem; border-radius: 7px; text-decoration: none; color: #374151; font-size: 0.875rem; font-weight: 500; transition: all 0.15s; margin-bottom: 2px; }
            .nav-link:hover { background: var(--primary-lt); color: var(--primary); }
            .nav-link.active { background: var(--primary-lt); color: var(--primary); font-weight: 600; }
            .nav-link i { width: 16px; text-align: center; font-size: 13px; }
            .sidebar-footer { padding: 1rem 0.75rem; border-top: 1px solid var(--border); }
            .user-chip { display: flex; align-items: center; gap: 0.6rem; padding: 0.5rem 0.75rem; background: var(--bg); border-radius: 8px; }
            .user-avatar { width: 30px; height: 30px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 700; color: white; flex-shrink: 0; }
            .user-name { font-size: 0.8rem; font-weight: 600; color: var(--text); }
            .user-role { font-size: 0.7rem; color: var(--text-muted); }
            .logout-btn { display: flex; align-items: center; gap: 0.5rem; width: 100%; padding: 0.45rem 0.75rem; border: none; background: none; cursor: pointer; color: var(--danger); font-size: 0.8rem; font-weight: 500; border-radius: 6px; margin-top: 0.5rem; transition: background 0.15s; text-align: left; }
            .logout-btn:hover { background: #fef2f2; }
            .main-wrapper { margin-left: var(--sidebar-w); min-height: 100vh; }
            .page-header { background: white; border-bottom: 1px solid var(--border); padding: 1.25rem 2rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50; }
            .page-header h1 { font-size: 1.15rem; font-weight: 700; margin: 0; color: var(--text); }
            .page-header p { font-size: 0.8rem; color: var(--text-muted); margin: 2px 0 0; }
            .page-content { padding: 1.75rem 2rem; }
            .card { background: white; border: 1px solid var(--border); border-radius: 12px; overflow: hidden; }
            .card-header { padding: 1rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
            .card-header h2 { font-size: 0.95rem; font-weight: 600; margin: 0; }
            .card-body { padding: 1.5rem; }
            .form-group { margin-bottom: 1.15rem; }
            .form-label { display: block; font-size: 0.8rem; font-weight: 600; color: #374151; margin-bottom: 0.35rem; }
            .form-control { width: 100%; padding: 0.6rem 0.9rem; border: 1.5px solid var(--border); border-radius: 8px; font-size: 0.875rem; font-family: 'Inter', sans-serif; transition: border-color 0.15s, box-shadow 0.15s; background: white; color: var(--text); }
            .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79,70,229,0.1); }
            textarea.form-control { resize: vertical; min-height: 90px; }
            .btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.55rem 1.1rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600; border: 1.5px solid transparent; cursor: pointer; transition: all 0.15s; text-decoration: none; }
            .btn-primary { background: var(--primary); color: white; border-color: var(--primary); }
            .btn-primary:hover { background: var(--primary-dp); border-color: var(--primary-dp); box-shadow: 0 4px 12px rgba(79,70,229,0.3); }
            .btn-success { background: var(--success); color: white; border-color: var(--success); }
            .btn-success:hover { background: #047857; box-shadow: 0 4px 12px rgba(5,150,105,0.25); }
            .btn-danger { background: var(--danger); color: white; border-color: var(--danger); }
            .btn-danger:hover { background: #b91c1c; }
            .btn-secondary { background: white; color: var(--text); border-color: var(--border); }
            .btn-secondary:hover { background: var(--bg); }
            .btn-sm { padding: 0.35rem 0.75rem; font-size: 0.775rem; border-radius: 6px; }
            .table-wrap { overflow-x: auto; border-radius: 12px; border: 1px solid var(--border); background: white; }
            table { width: 100%; border-collapse: collapse; }
            thead tr { background: #f9fafb; }
            th { padding: 0.75rem 1.25rem; text-align: left; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); border-bottom: 1px solid var(--border); white-space: nowrap; }
            td { padding: 0.85rem 1.25rem; font-size: 0.875rem; border-bottom: 1px solid #f3f4f6; color: var(--text); }
            tr:last-child td { border-bottom: none; }
            tbody tr:hover { background: #fafafa; }
            .badge { display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.22rem 0.6rem; border-radius: 100px; font-size: 0.72rem; font-weight: 600; }
            .badge-indigo { background: var(--primary-lt); color: var(--primary); }
            .badge-green { background: #d1fae5; color: #065f46; }
            .badge-yellow { background: #fef3c7; color: #92400e; }
            .badge-red { background: #fee2e2; color: #991b1b; }
            .badge-gray { background: #f3f4f6; color: #374151; }
            .badge-purple { background: #ede9fe; color: #5b21b6; }
            .alert { padding: 0.85rem 1.1rem; border-radius: 8px; font-size: 0.875rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.6rem; }
            .alert-success { background: #ecfdf5; color: #065f46; border-left: 4px solid var(--success); }
            .alert-danger { background: #fef2f2; color: #991b1b; border-left: 4px solid var(--danger); }
            .empty-state { text-align: center; padding: 3.5rem 2rem; }
            .empty-icon { width: 60px; height: 60px; border-radius: 50%; background: #f3f4f6; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem; font-size: 1.4rem; color: var(--text-muted); }
            .empty-state h3 { font-size: 1rem; font-weight: 600; margin: 0 0 0.35rem; }
            .empty-state p { color: var(--text-muted); font-size: 0.875rem; margin: 0; }
            .stat-card { background: white; border: 1px solid var(--border); border-radius: 12px; padding: 1.25rem 1.5rem; display: flex; align-items: flex-start; justify-content: space-between; }
            .stat-label { font-size: 0.78rem; font-weight: 500; color: var(--text-muted); }
            .stat-value { font-size: 2rem; font-weight: 800; color: var(--text); margin: 0.25rem 0; line-height: 1; }
            .stat-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }
            .file-drop { border: 2px dashed #d1d5db; border-radius: 10px; padding: 1.5rem; text-align: center; cursor: pointer; transition: all 0.2s; background: #fcfcfe; }
            .file-drop:hover { border-color: var(--primary); background: var(--primary-lt); }
            .file-drop i { font-size: 2rem; color: #a5b4fc; display: block; margin-bottom: 0.5rem; }
            .grid { display: grid; gap: 1.25rem; }
            .grid-2 { grid-template-columns: repeat(2, 1fr); }
            .grid-4 { grid-template-columns: repeat(4, 1fr); }
            .grid-3 { grid-template-columns: repeat(3, 1fr); }
            @media (max-width: 1024px) { .grid-4 { grid-template-columns: repeat(2, 1fr); } .grid-3 { grid-template-columns: 1fr; } }
            @media (max-width: 768px) { .sidebar { transform: translateX(-100%); } .main-wrapper { margin-left: 0; } .page-content { padding: 1rem; } .grid-2 { grid-template-columns: 1fr; } .grid-4 { grid-template-columns: 1fr; } }
            .section-gap { margin-bottom: 1.5rem; }
            .divider { height: 1px; background: var(--border); margin: 1.5rem 0; }
            .text-muted { color: var(--text-muted); }
        </style>
    </head>
    <body>
        <div class="sidebar">
            <div class="sidebar-logo">
                <div class="logo-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                <div class="logo-text">
                    <span>ProcureFlow</span>
                    <small>Procure-to-Pay System</small>
                </div>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-section-label">Main</div>
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i> Dashboard
                </a>
                @if(in_array(Auth::user()->role, ['admin', 'super_admin', 'requester']))
                <a href="{{ route('requisitions.index') }}" class="nav-link {{ request()->routeIs('requisitions.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> My Requisitions
                </a>
                @endif

                @if(in_array(Auth::user()->role, ['approver_stage1', 'approver_stage2']))
                <div class="nav-section-label">Approvals</div>
                <a href="{{ route('approvals.index') }}" class="nav-link {{ request()->routeIs('approvals.*') ? 'active' : '' }}">
                    <i class="fas fa-check-circle"></i> Pending Approvals
                </a>
                @endif

                @if(Auth::user()->role === 'accounts')
                <div class="nav-section-label">Finance</div>
                <a href="{{ route('invoices.index') }}" class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i> Invoice Review
                </a>
                @endif

                @if(in_array(Auth::user()->role, ['admin', 'super_admin']))
                <div class="nav-section-label">Administration</div>
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> User Management
                </a>
                <a href="{{ route('admin.departments.index') }}" class="nav-link {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">
                    <i class="fas fa-building"></i> Departments
                </a>
                @endif

                @if(Auth::user()->role === 'super_admin')
                <div class="nav-section-label" style="color:#7c3aed;margin-top:0.5rem">Super Admin</div>
                <a href="{{ route('superadmin.roles.index') }}" class="nav-link {{ request()->routeIs('superadmin.roles.*') ? 'active' : '' }}" style="{{ request()->routeIs('superadmin.roles.*') ? 'color:#5b21b6' : '' }}">
                    <i class="fas fa-key" style="color:#7c3aed"></i> Roles & Permissions
                </a>
                <a href="{{ route('superadmin.audit.index') }}" class="nav-link {{ request()->routeIs('superadmin.audit.*') ? 'active' : '' }}" style="{{ request()->routeIs('superadmin.audit.*') ? 'color:#5b21b6' : '' }}">
                    <i class="fas fa-shield-alt" style="color:#7c3aed"></i> Security & Audit Logs
                </a>
                @endif
            </nav>
            <div class="sidebar-footer">
                <div class="user-chip">
                    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                    <div>
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-role">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="logout-btn" type="submit">
                        <i class="fas fa-sign-out-alt"></i> Sign Out
                    </button>
                </form>
            </div>
        </div>
        <div class="main-wrapper">
            @isset($header)
                <div class="page-header">
                    <div>
                        {{ $header }}
                    </div>
                    @isset($headerActions)
                        <div>{{ $headerActions }}</div>
                    @endisset
                </div>
            @endisset
            <div class="page-content">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
