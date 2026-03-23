<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\IdentificationController;
use App\Http\Controllers\QuestionsController;

Route::get('/identification', [IdentificationController::class, 'showForm'])->name('identification');
Route::post('/identification', [IdentificationController::class, 'submitForm'])->name('identification.submit');

Route::get('/questions', [QuestionsController::class, 'index'])->name('questions.index');
Route::post('/questions', [QuestionsController::class, 'store'])->name('questions.store');
