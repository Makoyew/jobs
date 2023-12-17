<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PluginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogController;


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
Route::get('/', [AuthController::class, 'loginForm'])->name('loginForm');
Route::post('/', [AuthController::class, 'login'])->name('login');
Route::post('/dashboard', [AuthController::class, 'login'])->name('dashboard');

Route::get('/register', [AuthController::class, 'registerForm'])->name('registerForm');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



Route::get('/plugins', [PluginController::class, 'index'])->name('plugins.index');
Route::get('/plugins/create', [PluginController::class, 'create'])->name('plugins.create');
Route::post('/plugins', [PluginController::class, 'store'])->name('plugins.store');
Route::delete('/plugins/{plugin}', [PluginController::class, 'destroy'])->name('plugins.destroy');
Route::patch('/plugins/{plugin}', [PluginController::class, 'update'])->name('plugins.update');



Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
Route::delete('/log/{id}', [LogController::class, 'destroy'])->name('log.delete');
Route::post('/clear-all-logs', [LogController::class, 'clearAllLogs'])->name('logs.clearAll');


Route::post('/plugins/{plugin}', [PluginController::class, 'download'])->name('plugin.download');


Route::get('/index', [DashboardController::class, 'index']);


// Route::get('/sendmail', [EmailController::class, 'sendMail']);

Route::get('/verification/{user}/{token}', [AuthController::class, 'verification']);
