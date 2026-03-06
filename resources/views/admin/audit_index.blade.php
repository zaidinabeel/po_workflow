<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 style="font-size:1.15rem;font-weight:700;margin:0">Security Audit Logs</h1>
            <p style="font-size:0.8rem;color:#6b7280;margin:2px 0 0">Super Admin unified trail of all system actions</p>
        </div>
    </x-slot>

    <div class="card">
        <div class="table-wrap" style="padding:0">
            @if($logs->count() > 0)
            <table style="margin:0;width:100%;border-collapse:collapse">
                <thead>
                    <tr style="background:#f9fafb;border-bottom:2px solid #e5e7eb">
                        <th style="padding:12px 16px;text-align:left;font-size:0.75rem;color:#6b7280;text-transform:uppercase;font-weight:700">Timestamp</th>
                        <th style="padding:12px 16px;text-align:left;font-size:0.75rem;color:#6b7280;text-transform:uppercase;font-weight:700">User</th>
                        <th style="padding:12px 16px;text-align:left;font-size:0.75rem;color:#6b7280;text-transform:uppercase;font-weight:700">Action Type</th>
                        <th style="padding:12px 16px;text-align:left;font-size:0.75rem;color:#6b7280;text-transform:uppercase;font-weight:700">Description</th>
                        <th style="padding:12px 16px;text-align:left;font-size:0.75rem;color:#6b7280;text-transform:uppercase;font-weight:700">IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr style="border-bottom:1px solid #f3f4f6">
                        <td style="padding:12px 16px;font-size:0.8rem;color:#6b7280;white-space:nowrap">
                            {{ $log->created_at->format('d M Y, h:i:s A') }}
                        </td>
                        <td style="padding:12px 16px">
                            @if($log->user)
                                <div style="font-weight:600;font-size:0.85rem;color:#111827">{{ $log->user->name }}</div>
                                <div style="font-size:0.7rem;color:#6b7280">{{ $log->user->email }}</div>
                            @else
                                <span style="font-size:0.8rem;color:#9ca3af;font-style:italic">System Action</span>
                            @endif
                        </td>
                        <td style="padding:12px 16px">
                            <span style="background:#f3f4f6;padding:4px 8px;border-radius:4px;font-family:monospace;font-size:0.75rem;color:#4f46e5;font-weight:600">
                                {{ strtoupper($log->action) }}
                            </span>
                        </td>
                        <td style="padding:12px 16px;font-size:0.85rem;color:#374151">
                            {{ $log->description }}
                            @if($log->model_type)
                                <span style="color:#9ca3af;font-size:0.75rem;margin-left:6px">— Reference: {{ $log->model_type }} #{{ $log->model_id }}</span>
                            @endif
                        </td>
                        <td style="padding:12px 16px;font-size:0.75rem;color:#9ca3af;font-family:monospace">
                            {{ $log->ip_address ?? '—' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div style="padding:3rem 1rem;text-align:center">
                <div style="font-size:2rem;color:#d1d5db;margin-bottom:1rem"><i class="fas fa-history"></i></div>
                <h3 style="font-size:1.1rem;color:#374151;margin:0">No audit logs yet</h3>
                <p style="font-size:0.85rem;color:#6b7280;margin:6px 0 0">System actions will appear here once users start interacting with the workflow.</p>
            </div>
            @endif
        </div>
        
        @if($logs->hasPages())
        <div style="padding:1rem 1.5rem;border-top:1px solid #e5e7eb">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
