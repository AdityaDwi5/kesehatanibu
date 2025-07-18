<?php

use App\Http\Controllers\IbuController;
use App\Http\Controllers\PemeriksaanController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('ibu', IbuController::class);
Route::get('/ibu/{id}/pemeriksaan/export-pdf', [App\Http\Controllers\PemeriksaanController::class, 'exportPdf'])->name('pemeriksaan.exportPdf');
Route::resource('pemeriksaan', PemeriksaanController::class)->except(['index', 'show', 'edit', 'update', 'destroy']);
Route::get('/laporan-klasifikasi', [\App\Http\Controllers\LaporanController::class, 'klasifikasiBulanan'])->name('laporan.klasifikasi');
Route::get('/user', [App\Http\Controllers\UserController::class, 'users'])->name('user');
Route::get('/user/add', [App\Http\Controllers\userController::class, 'create'])->name('user-add');
Route::post('/user/store', [App\Http\Controllers\userController::class, 'store'])->name('user-store');
Route::get('/user/edit/{id}', [App\Http\Controllers\userController::class, 'edit'])->name('user-edit');
Route::post('/user/update', [App\Http\Controllers\userController::class, 'update'])->name('user-update');
Route::get('/user/delete/{id}', [App\Http\Controllers\UserController::class, 'delete'])->name('user-delete');
