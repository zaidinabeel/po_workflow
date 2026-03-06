<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 style="font-size:1.15rem;font-weight:700;margin:0">User Management</h1>
            <p style="font-size:0.8rem;color:#6b7280;margin:2px 0 0">Manage all system users and their roles</p>
        </div>
    </x-slot>
    <x-slot name="headerActions">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add User
        </a>
    </x-slot>

    @if(session('success'))
        <div class="alert alert-success section-gap"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Role</th>
                    <th>Department</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:0.65rem">
                            <div style="width:30px;height:30px;border-radius:50%;background:#eef2ff;color:#4f46e5;display:flex;align-items:center;justify-content:center;font-size:0.8rem;font-weight:700;flex-shrink:0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span style="font-weight:500">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td style="color:#6b7280">{{ $user->email }}</td>
                    <td style="color:#6b7280">{{ $user->contact_no ?? '—' }}</td>
                    <td>
                            @php
                                $roleBadge = match($user->role) {
                                    'super_admin' => 'badge-purple',
                                    'admin' => 'badge-red',
                                    'requester' => 'badge-indigo',
                                    'approver_stage1', 'approver_stage2' => 'badge-yellow',
                                    'accounts' => 'badge-green',
                                    default => 'badge-gray'
                                };
                            @endphp
                            <span class="badge {{ $roleBadge }}">
                                @if($user->role === 'super_admin') <i class="fas fa-crown"></i> @endif
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </td>
                        <td style="color:#374151">{{ $user->department->name ?? '—' }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:0.4rem;justify-content:flex-end">
                                @if(Auth::user()->role === 'super_admin')
                                    <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-secondary btn-sm" style="color:#059669;background:#f0fdf4;border:none">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('superadmin.users.reset-password', $user) }}" method="POST" onsubmit="return confirm('Reset password to Admin@2026?')">
                                        @csrf
                                        <button type="submit" class="btn btn-secondary btn-sm" style="color:#d97706;background:#fffbeb;border:none">
                                            <i class="fas fa-key"></i> Reset
                                        </button>
                                    </form>
                                @endif
                                
                                @if(Auth::id() !== $user->id)
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this user?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" style="border:none">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
