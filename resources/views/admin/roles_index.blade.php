<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 style="font-size:1.15rem;font-weight:700;margin:0">Roles & Permissions</h1>
            <p style="font-size:0.8rem;color:#6b7280;margin:2px 0 0">System role definitions and permission matrix</p>
        </div>
    </x-slot>

    {{-- ── Role Cards ────────────────────────────────────────────────────── --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.25rem;margin-bottom:2rem">
        @foreach($roles as $roleKey => $role)
        <div class="card" style="border-top:3px solid {{ $role['color'] }}">
            <div style="padding:1.25rem 1.5rem">
                {{-- Header --}}
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem">
                    <div style="display:flex;align-items:center;gap:0.65rem">
                        <div style="width:38px;height:38px;border-radius:10px;background:{{ $role['bg'] }};display:flex;align-items:center;justify-content:center">
                            <i class="fas {{ $role['icon'] }}" style="color:{{ $role['color'] }};font-size:1rem"></i>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:0.9rem;color:#111827">{{ $role['label'] }}</div>
                            <div style="font-size:0.7rem;font-family:monospace;color:#9ca3af">{{ $roleKey }}</div>
                        </div>
                    </div>
                    <span style="font-size:0.72rem;font-weight:700;background:{{ $role['bg'] }};color:{{ $role['color'] }};padding:3px 10px;border-radius:20px">
                        {{ ($usersByRole[$roleKey] ?? collect())->count() }} users
                    </span>
                </div>

                {{-- Description --}}
                <p style="font-size:0.8rem;color:#6b7280;margin:0 0 0.85rem;line-height:1.5">{{ $role['description'] }}</p>

                {{-- Permissions --}}
                <div style="background:#f9fafb;border-radius:8px;padding:0.75rem">
                    <div style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#9ca3af;margin-bottom:0.5rem">Permissions</div>
                    @foreach($permissions[$roleKey] as $perm)
                    <div style="display:flex;align-items:center;gap:0.4rem;padding:3px 0;font-size:0.78rem;color:#374151">
                        <i class="fas fa-check" style="color:{{ $role['color'] }};font-size:0.6rem;flex-shrink:0"></i>
                        {{ $perm }}
                    </div>
                    @endforeach
                </div>

                {{-- Users Assigned --}}
                @if(($usersByRole[$roleKey] ?? collect())->count() > 0)
                <div style="margin-top:0.85rem">
                    <div style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#9ca3af;margin-bottom:0.4rem">Assigned Users</div>
                    <div style="display:flex;flex-wrap:wrap;gap:0.4rem">
                        @foreach($usersByRole[$roleKey] as $u)
                        <span style="background:{{ $role['bg'] }};color:{{ $role['color'] }};font-size:0.72rem;font-weight:600;padding:3px 9px;border-radius:20px;display:flex;align-items:center;gap:0.35rem">
                            <span style="width:18px;height:18px;background:{{ $role['color'] }};color:white;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:0.6rem;font-weight:800">{{ strtoupper(substr($u->name,0,1)) }}</span>
                            {{ $u->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Quick-assign CTA --}}
            <div style="padding:0.75rem 1.5rem;border-top:1px solid #f3f4f6;background:#fafafa;display:flex;justify-content:space-between;align-items:center">
                <span style="font-size:0.75rem;color:#9ca3af">To assign this role, edit a user</span>
                <a href="{{ route('admin.users.index') }}" style="font-size:0.75rem;font-weight:600;color:{{ $role['color'] }};text-decoration:none">
                    Manage Users <i class="fas fa-arrow-right" style="font-size:0.65rem"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── Permission Matrix Table ──────────────────────────────────────── --}}
    <div class="card">
        <div class="card-header">
            <h2>Permission Matrix</h2>
            <span style="font-size:0.78rem;color:#6b7280">Full comparison of all role access levels</span>
        </div>
        <div style="overflow-x:auto">
            <table style="margin:0;border-collapse:collapse;width:100%;min-width:800px">
                <thead>
                    <tr style="background:#f9fafb">
                        <th style="padding:12px 16px;text-align:left;font-size:0.72rem;color:#6b7280;font-weight:700;text-transform:uppercase;border-bottom:1px solid #e5e7eb">Permission / Feature</th>
                        @foreach($roles as $rk => $r)
                        <th style="padding:12px 10px;text-align:center;font-size:0.72rem;font-weight:700;text-transform:uppercase;border-bottom:1px solid #e5e7eb;color:{{ $r['color'] }}">{{ $r['label'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                    $matrixRows = [
                        'View Dashboard'       => ['super_admin','admin','requester','approver_stage1','approver_stage2','accounts'],
                        'Create PR'            => ['super_admin','admin','requester'],
                        'Upload Quotations'    => ['super_admin','admin','requester'],
                        'Select Quotations'    => ['super_admin','admin','requester'],
                        'Stage 1 Approval'     => ['super_admin','approver_stage1'],
                        'Stage 2 Approval'     => ['super_admin','approver_stage2'],
                        'Manage Users'         => ['super_admin','admin'],
                        'Manage Departments'   => ['super_admin','admin'],
                        'Edit / Reset Users'   => ['super_admin'],
                        'View Invoices'        => ['super_admin','accounts'],
                        'Mark Invoice Paid'    => ['super_admin','accounts'],
                        'View Security Audit'  => ['super_admin'],
                        'Assign Roles'         => ['super_admin'],
                    ];
                    @endphp
                    @foreach($matrixRows as $feat => $allowedRoles)
                    <tr style="border-bottom:1px solid #f3f4f6">
                        <td style="padding:10px 16px;font-size:0.82rem;font-weight:600;color:#374151">{{ $feat }}</td>
                        @foreach($roles as $rk => $r)
                        <td style="padding:10px 10px;text-align:center">
                            @if(in_array($rk, $allowedRoles))
                            <i class="fas fa-check-circle" style="color:{{ $r['color'] }};font-size:1rem"></i>
                            @else
                            <i class="fas fa-minus" style="color:#d1d5db;font-size:0.9rem"></i>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
