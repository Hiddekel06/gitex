<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\IdentificationController;
use App\Http\Controllers\AdminAuthController;

// Dashboard admin protégé
Route::get('/admin/dashboard', function () {
	return view('admin.dashboard');
})->middleware('admin.auth')->name('admin.dashboard');

// Routes admin
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

Route::get('/', function () {
	return view('welcome');
});

Route::get('/identification', [IdentificationController::class, 'showForm'])->name('identification');
Route::post('/identification', [IdentificationController::class, 'submitForm'])->name('identification.submit');

Route::get('/questions', [QuestionsController::class, 'index'])->name('questions.index');
Route::post('/questions', [QuestionsController::class, 'store'])->name('questions.store');
