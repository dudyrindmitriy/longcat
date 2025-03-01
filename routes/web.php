<?php

use App\Http\Controllers\VisitorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/visitors/create', [VisitorController::class, 'create'])->name('visitors.create');
Route::post('/visitors/store', [VisitorController::class, 'store'])->name('visitors.store');

