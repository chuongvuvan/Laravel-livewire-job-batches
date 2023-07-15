<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('dev-login', function () {
    abort_unless(app()->environment('local'), 403);

    auth()->loginUsingId(\App\Models\User::first()->id);

    return redirect()->to('/');
});
