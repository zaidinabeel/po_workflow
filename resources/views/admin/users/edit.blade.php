<x-app-layout>
    <x-slot name="header">
        <div>
            <div style="display:flex;align-items:center;gap:0.5rem">
                <a href="{{ route('admin.users.index') }}" style="color:#6b7280;text-decoration:none"><i class="fas fa-arrow-left"></i></a>
                <h1 style="font-size:1.15rem;font-weight:700;margin:0">Edit User — {{ $user->name }}</h1>
                @if($user->role === 'super_admin') <span class="badge badge-purple" style="margin-left:8px"><i class="fas fa-crown"></i> Super Admin</span> @endif
            </div>
            <p style="font-size:0.8rem;color:#6b7280;margin:2px 0 0;margin-left:1.75rem">Update user information, role, and department assignment</p>
        </div>
    </x-slot>

    <div style="max-width:800px;margin:0 auto">
        <div class="card">
            <div class="card-header" style="background:#f9fafb">
                <h2 style="font-size:1.05rem;color:#111827">User Details</h2>
            </div>
            <div style="padding:1.5rem">
                <form action="{{ route('superadmin.users.update', $user) }}" method="POST">
                    @csrf @method('PATCH')
                    
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem">
                        <!-- Name -->
                        <div>
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            @error('name')<span style="color:#dc2626;font-size:0.75rem">{{ $message }}</span>@enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            @error('email')<span style="color:#dc2626;font-size:0.75rem">{{ $message }}</span>@enderror
                        </div>

                        <!-- Contact -->
                        <div>
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_no" class="form-control" value="{{ old('contact_no', $user->contact_no) }}">
                        </div>

                        <!-- Role -->
                        <div>
                            <label class="form-label">System Role</label>
                            <select name="role" class="form-control" required style="{{ $user->id === Auth::id() ? 'opacity:0.6;pointer-events:none' : '' }}">
                                <option value="requester" {{ $user->role === 'requester' ? 'selected' : '' }}>Requester (Procurement)</option>
                                <option value="approver_stage1" {{ $user->role === 'approver_stage1' ? 'selected' : '' }}>Stage 1 Approver (Compliance)</option>
                                <option value="approver_stage2" {{ $user->role === 'approver_stage2' ? 'selected' : '' }}>Stage 2 Approver (IT)</option>
                                <option value="accounts" {{ $user->role === 'accounts' ? 'selected' : '' }}>Accounts</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin (Users & Departments)</option>
                                <option value="super_admin" {{ $user->role === 'super_admin' ? 'selected' : '' }}>Super Admin (Full Access & Secruity)</option>
                            </select>
                            @if($user->id === Auth::id())
                                <p style="font-size:0.75rem;color:#9ca3af;margin:4px 0 0">You cannot change your own role.</p>
                                <input type="hidden" name="role" value="{{ $user->role }}">
                            @endif
                        </div>

                        <!-- Department -->
                        <div style="grid-column:1 / -1">
                            <label class="form-label">Department Assignment</label>
                            <select name="department_id" class="form-control">
                                <option value="">-- No Department --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ $user->department_id == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div style="display:flex;justify-content:flex-end;gap:1rem;padding-top:1rem;border-top:1px solid #f3f4f6">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
            
        </div>
        
        <!-- Danger Zone -->
        <div class="card" style="margin-top:2rem;border:1px solid #fecaca">
            <div class="card-header" style="background:#fef2f2;border-bottom:1px solid #fecaca">
                <h2 style="font-size:1.05rem;color:#991b1b"><i class="fas fa-exclamation-triangle"></i> Danger Zone</h2>
            </div>
            <div style="padding:1.5rem;display:flex;align-items:center;justify-content:space-between">
                <div>
                    <strong style="color:#111827;display:block">Reset Password</strong>
                    <span style="font-size:0.85rem;color:#6b7280">This will immediately reset the user's password to the system default <code style="background:#f3f4f6;padding:2px 6px;border-radius:4px;color:#dc2626">Admin@2026</code></span>
                </div>
                <form action="{{ route('superadmin.users.reset-password', $user) }}" method="POST" onsubmit="return confirm('Immediately reset this user\'s password to Admin@2026? This cannot be undone.')">
                    @csrf
                    <button class="btn btn-secondary" style="border-color:#fca5a5;color:#dc2626;background:white">Reset Password</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
