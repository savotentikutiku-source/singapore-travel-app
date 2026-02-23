<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\TravelController;

Route::get('/travel', [TravelController::class, 'index']);

Route::post('/travel/schedule', [TravelController::class, 'storeSchedule']);

Route::post('/travel/checklist', [TravelController::class, 'storeChecklist']);

Route::patch('/travel/checklist/{id}/toggle', [TravelController::class, 'toggleChecklist']);

Route::post('/travel/expense', [TravelController::class, 'storeExpense']);