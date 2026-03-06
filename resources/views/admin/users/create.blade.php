<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 style="font-size:1.15rem;font-weight:700;margin:0">Create New User</h1>
            <p style="font-size:0.8rem;color:#6b7280;margin:2px 0 0">
                <a href="{{ route('admin.users.index') }}" style="color:#4f46e5;text-decoration:none">User Management</a> &rsaquo; New User
            </p>
        </div>
    </x-slot>

    <div style="max-width:580px">
        <div class="card">
            <div class="card-header"><h2><i class="fas fa-user-plus" style="color:#4f46e5;margin-right:0.4rem"></i>User Details</h2></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="grid grid-2" style="gap:1rem;margin-bottom:0">
                        <div class="form-group">
                            <label class="form-label">Full Name <span style="color:red">*</span></label>
                            <input type="text" name="name" required class="form-control" value="{{ old('name') }}" placeholder="John Smith">
                            @error('name')<p style="color:#dc2626;font-size:0.78rem;margin-top:0.25rem">{{ $message }}</p>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Address <span style="color:red">*</span></label>
                            <input type="email" name="email" required class="form-control" value="{{ old('email') }}" placeholder="john@example.com">
                            @error('email')<p style="color:#dc2626;font-size:0.78rem;margin-top:0.25rem">{{ $message }}</p>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_no" class="form-control" value="{{ old('contact_no') }}" placeholder="+1 234 567 8900">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Role <span style="color:red">*</span></label>
                            <select name="role" required class="form-control">
                                <option value="">-- Select Role --</option>
                                <option value="admin" {{ old('role')=='admin'?'selected':'' }}>Admin</option>
                                <option value="requester" {{ old('role')=='requester'?'selected':'' }}>Requester</option>
                                <option value="approver_stage1" {{ old('role')=='approver_stage1'?'selected':'' }}>Approver Stage 1</option>
                                <option value="approver_stage2" {{ old('role')=='approver_stage2'?'selected':'' }}>Approver Stage 2</option>
                                <option value="accounts" {{ old('role')=='accounts'?'selected':'' }}>Accounts</option>
                            </select>
                            @error('role')<p style="color:#dc2626;font-size:0.78rem;margin-top:0.25rem">{{ $message }}</p>@enderror
                        </div>
                        <div class="form-group" style="grid-column:span 2">
                            <label class="form-label">Department</label>
                            <select name="department_id" class="form-control">
                                <option value="">-- No Department --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id')==$dept->id?'selected':'' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;padding:0.75rem 1rem;margin-bottom:1.25rem;font-size:0.8rem;color:#0369a1">
                        <i class="fas fa-info-circle" style="margin-right:0.35rem"></i>
                        Default password will be set to: <strong>Admin@2026</strong>
                    </div>
                    <div style="display:flex;gap:0.75rem;justify-content:flex-end">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus"></i> Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
