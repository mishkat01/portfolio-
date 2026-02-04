<?php

use Illuminate\Support\Facades\Route;

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Guest Admin Routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login']);
    });

    // Authenticated Admin Routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index']); 
        Route::post('logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('logout');
        
        // User Management Routes
        Route::middleware(['permission:manage-users'])->resource('users', App\Http\Controllers\Admin\UserManagementController::class)->except(['show', 'create', 'store']);
        
        // Resource Routes
        Route::middleware(['superadmin'])->group(function () {
             Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);
             Route::resource('admins', App\Http\Controllers\Admin\AdminManagementController::class);
             
             // Global Settings
             Route::get('settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
             Route::post('settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        });
    });
});
