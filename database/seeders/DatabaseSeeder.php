<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vendor;
use App\Models\PurchaseOrder;
use App\Models\Department;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Departments
        $complianceDept = Department::create([
            'name' => 'Compliance',
            'description' => 'Handles Stage 1 Approvals for Quotations'
        ]);

        $itDept = Department::create([
            'name' => 'IT Department',
            'description' => 'Handles Stage 2 Approvals for Quotations'
        ]);

        $accountsDept = Department::create([
            'name' => 'Accounts',
            'description' => 'Handles final invoice payments'
        ]);

        $procurementDept = Department::create([
            'name' => 'Procurement',
            'description' => 'Initiates PO Requests'
        ]);

        // 2. Create Default Users (Password explicitly 'Admin@2026')
        $defaultPassword = bcrypt('Admin@2026');

        // Admin User (Limited)
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@example.com',
            'password' => $defaultPassword,
            'role' => 'admin',
        ]);

        // Super Admin (Full Security)
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => $defaultPassword,
            'role' => 'super_admin',
        ]);

        // Requester User
        User::create([
            'name' => 'John Requester',
            'email' => 'requester@example.com',
            'password' => $defaultPassword,
            'contact_no' => '123-456-7890',
            'department_id' => $procurementDept->id,
            'role' => 'requester',
        ]);

        // Stage 1 Approver (Compliance)
        User::create([
            'name' => 'Sarah Compliance',
            'email' => 'compliance@example.com',
            'password' => $defaultPassword,
            'contact_no' => '555-010-1010',
            'department_id' => $complianceDept->id,
            'role' => 'approver_stage1',
        ]);

        // Stage 2 Approver (IT)
        User::create([
            'name' => 'Mike IT',
            'email' => 'it@example.com',
            'password' => $defaultPassword,
            'contact_no' => '555-020-2020',
            'department_id' => $itDept->id,
            'role' => 'approver_stage2',
        ]);

        // Accounts User
        User::create([
            'name' => 'Emma Accounts',
            'email' => 'accounts@example.com',
            'password' => $defaultPassword,
            'contact_no' => '555-030-3030',
            'department_id' => $accountsDept->id,
            'role' => 'accounts',
        ]);

        // Seed Vendors (for later use)
        $vendors = [
            Vendor::create(['name' => 'Acme Corp', 'email' => 'contact@acmecorp.com', 'phone' => '123-456-7890']),
            Vendor::create(['name' => 'TechSolutions Inc.', 'email' => 'sales@techsolutions.com', 'phone' => '098-765-4321']),
            Vendor::create(['name' => 'Global Supplies LLC', 'email' => 'orders@globalsupplies.com', 'phone' => '555-123-4567']),
        ];
    }
}
