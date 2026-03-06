<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\User;
use App\Models\Department;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // ========================
    // User Management
    // ========================
    
    public function usersIndex()
    {
        $users = User::with('department')->orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }

    public function usersCreate()
    {
        $departments = Department::orderBy('name')->get();
        return view('admin.users.create', compact('departments'));
    }

    public function usersStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'contact_no' => 'nullable|string|max:50',
            'role' => 'required|in:admin,requester,approver_stage1,approver_stage2,accounts',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact_no' => $request->contact_no,
            'role' => $request->role,
            'department_id' => $request->department_id,
            'password' => Hash::make('Admin@2026'), // default password
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created. Default password: Admin@2026');
    }

    public function usersDestroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User deleted.');
    }

    public function usersEdit(User $user)
    {
        $departments = Department::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'departments'));
    }

    public function usersUpdate(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'contact_no' => 'nullable|string|max:50',
            'role' => 'required|in:admin,requester,approver_stage1,approver_stage2,accounts,super_admin',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'contact_no' => $request->contact_no,
            'role' => $request->role,
            'department_id' => $request->department_id,
        ]);

        AuditLog::record('user_updated', "User {$user->name} details updated", $user);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function usersResetPassword(User $user)
    {
        $user->update(['password' => Hash::make('Admin@2026')]);
        AuditLog::record('password_reset', "Password reset to default for {$user->name}", $user);
        return back()->with('success', "Password reset to Admin@2026 for {$user->name}.");
    }

    // ========================
    // Audit Logs (Super Admin)
    // ========================

    public function auditIndex()
    {
        $logs = AuditLog::with('user')->orderBy('created_at', 'desc')->paginate(50);
        return view('admin.audit_index', compact('logs'));
    }

    // ========================
    // Roles & Permissions
    // ========================

    public function rolesIndex()
    {
        $roles = [
            'super_admin'     => ['label' => 'Super Admin',       'color' => '#7c3aed', 'bg' => '#ede9fe', 'icon' => 'fa-crown',         'description' => 'Full system access, user management, audit logs and security controls.'],
            'admin'           => ['label' => 'Admin',             'color' => '#dc2626', 'bg' => '#fee2e2', 'icon' => 'fa-user-shield',   'description' => 'User & department management. Cannot access security/audit features.'],
            'requester'       => ['label' => 'Requester',         'color' => '#4f46e5', 'bg' => '#eef2ff', 'icon' => 'fa-file-alt',      'description' => 'Creates purchase requisitions, uploads quotations, and tracks PR status.'],
            'approver_stage1' => ['label' => 'Stage 1 Approver',  'color' => '#d97706', 'bg' => '#fef3c7', 'icon' => 'fa-check-circle',  'description' => 'Reviews and approves/rejects PRs at Stage 1 (Compliance review).'],
            'approver_stage2' => ['label' => 'Stage 2 Approver',  'color' => '#d97706', 'bg' => '#fef3c7', 'icon' => 'fa-check-double',  'description' => 'Final approver at Stage 2 (IT/management). Triggers PO generation.'],
            'accounts'        => ['label' => 'Accounts',          'color' => '#059669', 'bg' => '#d1fae5', 'icon' => 'fa-receipt',       'description' => 'Reviews uploaded invoices and marks payments as Done or Pending.'],
        ];

        $permissions = [
            'super_admin'     => ['View Dashboard', 'Create PR', 'Manage Users', 'Manage Departments', 'Edit/Reset Users', 'View All PRs', 'View Audit Logs', 'Assign Roles'],
            'admin'           => ['View Dashboard', 'Create PR', 'Manage Users', 'Manage Departments', 'View All PRs'],
            'requester'       => ['View Dashboard', 'Create PR', 'Upload Quotations', 'Select Quotations', 'Track Own PRs'],
            'approver_stage1' => ['View Dashboard', 'Review Stage 1 PRs', 'Approve / Reject PRs', 'Add Comments'],
            'approver_stage2' => ['View Dashboard', 'Review Stage 2 PRs', 'Approve / Reject PRs', 'Trigger PO Generation'],
            'accounts'        => ['View Dashboard', 'View Invoices', 'Mark Invoice as Paid', 'Mark Invoice as Pending'],
        ];

        $usersByRole = User::with('department')->get()->groupBy('role');

        return view('admin.roles_index', compact('roles', 'permissions', 'usersByRole'));
    }

    // ========================
    // Department Management
    // ========================
    
    public function departmentsIndex()
    {
        $departments = Department::withCount('users')->orderBy('name')->get();
        return view('admin.departments.index', compact('departments'));
    }

    public function departmentsCreate()
    {
        return view('admin.departments.create');
    }

    public function departmentsStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'description' => 'nullable|string',
        ]);

        Department::create($request->only('name', 'description'));

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department created successfully.');
    }
    
    public function departmentsDestroy(Department $department)
    {
        $department->delete();
        return back()->with('success', 'Department deleted.');
    }
}
