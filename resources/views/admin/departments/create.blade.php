<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 style="font-size:1.15rem;font-weight:700;margin:0">Create Department</h1>
            <p style="font-size:0.8rem;color:#6b7280;margin:2px 0 0">
                <a href="{{ route('admin.departments.index') }}" style="color:#4f46e5;text-decoration:none">Departments</a> &rsaquo; New
            </p>
        </div>
    </x-slot>

    <div style="max-width:500px">
        <div class="card">
            <div class="card-header"><h2><i class="fas fa-building" style="color:#4f46e5;margin-right:0.4rem"></i>Department Details</h2></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.departments.store') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Department Name <span style="color:red">*</span></label>
                        <input type="text" name="name" required class="form-control" value="{{ old('name') }}" placeholder="e.g. Finance, IT, Procurement">
                        @error('name')<p style="color:#dc2626;font-size:0.78rem;margin-top:0.25rem">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description (Optional)</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Brief description of this department...">{{ old('description') }}</textarea>
                    </div>
                    <div style="display:flex;gap:0.75rem;justify-content:flex-end">
                        <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-building"></i> Create Department</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
