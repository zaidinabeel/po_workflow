<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 style="font-size:1.15rem;font-weight:700;margin:0">Create Purchase Requisition</h1>
            <p style="font-size:0.8rem;color:#6b7280;margin:2px 0 0">Submit a new purchase request to begin the procurement process</p>
        </div>
    </x-slot>

    <div style="max-width:640px">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-file-alt" style="color:#4f46e5;margin-right:0.5rem"></i>Requisition Details</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('requisitions.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Requisition Title <span style="color:red">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. New Laptops for Development Team" value="{{ old('title') }}" required>
                        @error('title')<p style="color:#dc2626;font-size:0.78rem;margin-top:0.25rem">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Detailed Description & Justification <span style="color:red">*</span></label>
                        <textarea name="description" class="form-control" rows="5" placeholder="Explain what you need, why it's required, and any urgency..." required>{{ old('description') }}</textarea>
                        @error('description')<p style="color:#dc2626;font-size:0.78rem;margin-top:0.25rem">{{ $message }}</p>@enderror
                    </div>
                    <div style="display:flex;gap:0.75rem;justify-content:flex-end;margin-top:1.5rem">
                        <a href="{{ route('requisitions.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-arrow-right"></i> Save & Add Quotations
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
