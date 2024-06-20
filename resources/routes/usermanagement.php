<?php

use App\Http\Controllers\ACL\PermissionController;
use App\Http\Controllers\ACL\RoleController;
use App\Http\Controllers\ACL\RolePermissionController;
use App\Http\Controllers\ACL\UserController;
use App\Http\Controllers\ACL\UserPermissionController;
use App\Http\Controllers\ACL\UserRoleController;
use Illuminate\Support\Facades\Route;

// Routes related to ACL (Access Control List)

Route::resource('/user-management', UserController::class)->names([
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