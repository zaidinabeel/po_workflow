<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 style="font-size:1.15rem;font-weight:700;margin:0">Departments</h1>
            <p style="font-size:0.8rem;color:#6b7280;margin:2px 0 0">Manage organizational departments</p>
        </div>
    </x-slot>
    <x-slot name="headerActions">
        <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Department
        </a>
    </x-slot>

    @if(session('success'))
        <div class="alert alert-success section-gap"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Department Name</th>
                    <th>Description</th>
                    <th>Total Users</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($departments as $dept)
                <tr>
                    <td style="font-weight:500">{{ $dept->name }}</td>
                    <td style="color:#6b7280">{{ $dept->description ?? '—' }}</td>
                    <td><span class="badge badge-gray">{{ $dept->users_count }} users</span></td>
                    <td>
                        <form action="{{ route('admin.departments.destroy', $dept) }}" method="POST" onsubmit="return confirm('Delete this department?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
