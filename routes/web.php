<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


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

// PUBLIC PAGE
Route::group(["prefix" => "page", "middleware" => "handleReLogin"], function () {
    Route::get('login', [UserController::class, 'ViewSignIn'])->name("login_page");
    Route::get('register', [UserController::class, 'ViewSignUp']);
});

// PROTECTED PAGE
Route::group(["prefix" => "page", "middleware" => "auth2"], function () {
    Route::get('books', [BookController::class, 'ViewBooks']);
    Route::get('books/{book_id}', [BookController::class, 'ViewBook']);
    Route::get('admin/books', [BookController::class, 'ViewAdminBooks']);
    Route::get('admin/books/{book_id}', [BookController::class, 'ViewAdminAddEditBooks']);
    Route::get('admin/users', [UserController::class, 'ViewAdminUsers']);
    Route::get('admin/users/{user_id}', [UserController::class, 'ViewAdminAddEditUsers']);
    Route::get('users/profile', [UserController::class, 'ViewUser']);
    Route::get('users/profile/setting', [UserController::class, 'ViewSettingUser']);
});

// OPEN API
Route::get('', [UserController::class, 'Default']);
Route::group(["prefix" => "api"], function () {
    Route::post('auth/sign_up', [UserController::class, 'AuthSignUp'])->name("sign_up");
    Route::post('auth/sign_in', [UserController::class, 'AuthSignIn'])->name("sign_in");
    Route::get('auth/sign_out', [UserController::class, 'SignOut'])->name("sign_out");
});

// PROTECTED API
Route::group(["prefix" => "api", "middleware" => "auth2"], function () {
    Route::post('books', [BookController::class, 'BookInsert']);
    Route::put('books/{book_id}', [BookController::class, 'BookUpdate']);
    Route::delete('books/{book_id}', [BookController::class, 'BookDelete']);
    Route::post('users', [UserController::class, 'UserInsert']);
    Route::put('users/{user_id}', [UserController::class, 'UserUpdate']);
    Route::delete('users/{user_id}', [UserController::class, 'UserDelete']);
    Route::put('users/profile/update', [UserController::class, 'UserUpdatePofile']);
});
