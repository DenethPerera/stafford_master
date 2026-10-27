<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->name('users.')->group(function () {

    // ─── User Account ─────────────────────────────────────────────────────────
    Route::get('account', [UserController::class, 'index'])->name('account')->middleware('permission:view-users');
    Route::post('register', [UserController::class, 'store'])->name('store')->middleware('permission:create-users');
    Route::get('users-data', [UserController::class, 'getUsersData'])->name('data')->middleware('permission:view-users');
    Route::get('{id}/edit', [UserController::class, 'edit'])->name('edit')->middleware('permission:edit-users');
    Route::put('{id}', [UserController::class, 'update'])->name('update')->middleware('permission:edit-users');
    Route::delete('{id}', [UserController::class, 'destroy'])->name('destroy')->middleware('permission:delete-users');
    Route::post('{id}/status', [UserController::class, 'updateStatus'])->name('status')->middleware('permission:edit-users');

    // ─── User Type / Roles ───────────────────────────────────────────────────
    Route::get('type', [UserController::class, 'typeIndex'])->name('type')->middleware('permission:manage-roles');
    Route::get('userstype-data', [UserController::class, 'getUsertypeData'])->name('typedata')->middleware('permission:manage-roles');
    Route::post('addusertype', [UserController::class, 'addUserType'])->name('addusertype')->middleware('permission:manage-roles');
    Route::get('typedata/{id}/edit', [UserController::class, 'editUserType'])->name('usertype.edit')->middleware('permission:manage-roles');
    Route::post('typedata/{id}/status', [UserController::class, 'updateUserTypeStatus'])->name('usertype.editstatus')->middleware('permission:manage-roles');
    Route::put('typedata/{id}', [UserController::class, 'updateUserType'])->name('usertype.update')->middleware('permission:manage-roles');
    Route::delete('typedata/{id}', [UserController::class, 'destroyUserType'])->name('usertype.destroy')->middleware('permission:manage-roles');

    // ─── User Privilege ───────────────────────────────────────────────────────
    Route::get('privileges', [UserController::class, 'privilegeIndex'])->name('privilege')->middleware('permission:manage-roles');
    Route::get('privilege-data', [UserController::class, 'getPrivilegeData'])->name('privilege.data')->middleware('permission:manage-roles');
    Route::post('privileges/add', [UserController::class, 'privilegeAdd'])->name('privilege.add')->middleware('permission:manage-roles');
    Route::get('privilege/{id}/edit', [UserController::class, 'getPrivilege'])->name('privilege.get')->middleware('permission:manage-roles');
    Route::put('privilege/{id}', [UserController::class, 'editPrivilege'])->name('privilege.update')->middleware('permission:manage-roles');
    Route::post('privilege/{id}/status', [UserController::class, 'updatePrivilegeStatus'])->name('privilege.status')->middleware('permission:manage-roles');
    Route::delete('privilege/{id}/delete', [UserController::class, 'deletePrivilege'])->name('privilege.destroy')->middleware('permission:manage-roles');
});
