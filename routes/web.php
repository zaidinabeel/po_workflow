<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PurchaseRequisitionController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\InvoiceController;

Route::get('/', function () {
    return redirect()->route('login');
});

// ✅ Public vendor upload route (no auth needed - token based)
Route::get('/vendor/upload/{token}', [InvoiceController::class, 'vendorShow'])->name('invoices.vendor.show');
Route::post('/vendor/upload/{token}', [InvoiceController::class, 'vendorStore'])->name('invoices.vendor.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('requisitions', PurchaseRequisitionController::class);
    Route::post('/requisitions/{requisition}/quotations', [QuotationController::class, 'store'])->name('quotations.store');
    Route::post('/requisitions/{requisition}/quotations/select', [QuotationController::class, 'select'])->name('quotations.select');

    Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');
    Route::post('/approvals/{requisition}', [ApprovalController::class, 'store'])->name('approvals.store');

    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');

    // Admin-only routes
    Route::middleware(\App\Http\Middleware\AdminOnly::class)->prefix('admin')->name('admin.')->group(function() {
        Route::get('/users', [\App\Http\Controllers\AdminController::class, 'usersIndex'])->name('users.index');
        Route::get('/users/create', [\App\Http\Controllers\AdminController::class, 'usersCreate'])->name('users.create');
        Route::post('/users', [\App\Http\Controllers\AdminController::class, 'usersStore'])->name('users.store');
        Route::delete('/users/{user}', [\App\Http\Controllers\AdminController::class, 'usersDestroy'])->name('users.destroy');

        Route::get('/departments', [\App\Http\Controllers\AdminController::class, 'departmentsIndex'])->name('departments.index');
        Route::get('/departments/create', [\App\Http\Controllers\AdminController::class, 'departmentsCreate'])->name('departments.create');
        Route::post('/departments', [\App\Http\Controllers\AdminController::class, 'departmentsStore'])->name('departments.store');
        Route::delete('/departments/{department}', [\App\Http\Controllers\AdminController::class, 'departmentsDestroy'])->name('departments.destroy');
    });

    // Super Admin-only routes
    Route::middleware('super_admin')->prefix('super-admin')->name('superadmin.')->group(function() {
        Route::get('/users/{user}/edit', [\App\Http\Controllers\AdminController::class, 'usersEdit'])->name('users.edit');
        Route::patch('/users/{user}', [\App\Http\Controllers\AdminController::class, 'usersUpdate'])->name('users.update');
        Route::post('/users/{user}/reset-password', [\App\Http\Controllers\AdminController::class, 'usersResetPassword'])->name('users.reset-password');
        
        Route::get('/audit', [\App\Http\Controllers\AdminController::class, 'auditIndex'])->name('audit.index');
        Route::get('/roles', [\App\Http\Controllers\AdminController::class, 'rolesIndex'])->name('roles.index');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Invoice payment actions (not requiring email verification)
    Route::post('/invoices/{invoice}/pay', [InvoiceController::class, 'markPaid'])->name('invoices.pay');
    Route::post('/invoices/{invoice}/pending', [InvoiceController::class, 'markPending'])->name('invoices.pending');
});

require __DIR__.'/auth.php';
