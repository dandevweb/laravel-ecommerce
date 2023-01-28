<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\UserPermissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')
    ->middleware(['auth', 'permission:view admin dashboard'])
	->name('admin.')
    ->group(static function () {
        Route::get('/', [HomeController::class, 'index'])->name('home.index');

        Route::resource('users', UserController::class);

        Route::resource('roles', RoleController::class);

        Route::post('/user/{user}/roles', UserRoleController::class)->name(
            'users.roles.assign',
        );
        Route::post(
            '/users/{user}/permissions',
            UserPermissionController::class,
        )->name('users.permissions.assign');

        Route::post(
            '/roles/{role}/permissions',
            RolePermissionController::class,
        )->name('roles.permissions.assign');




    });