<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChangelogController;

Route::get('/changelog', [ChangelogController::class, 'show'])->name('changelog');
