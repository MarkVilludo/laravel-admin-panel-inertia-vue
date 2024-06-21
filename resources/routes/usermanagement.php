<?php

use App\Http\Controllers\UserManagement\PermissionController;
use App\Http\Controllers\UserManagement\RoleController;
use App\Http\Controllers\UserManagement\UserManagementController;
use Illuminate\Support\Facades\Route;

// Routes related to ACL (Access Control List)

Route::resource('/user-management', UserManagementController::class)->names([
    'index'   => 'user-management.index',
    'store'   => 'user-management.store',
    'update'  => 'user-management.update',
    'destroy' => 'user-management.destroy',
]);

Route::resource('/permissions', PermissionController::class)->names([
    'index'   => 'permissions.index',
    'store'   => 'permissions.store',
    'update'  => 'permissions.update',
    'destroy' => 'permissions.destroy',
]);
Route::put('/permissions/update/status', [PermissionController::class, 'status'])->name('permissions.status');

Route::resource('/roles', RoleController::class)->names([
    'index'   => 'roles.index',
    'store'   => 'roles.store',
    'update'  => 'roles.update',
    'destroy' => 'roles.destroy',
]);
Route::put('/roles/update/status', [RoleController::class, 'status'])->name('roles.status');