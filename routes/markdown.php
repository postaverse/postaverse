<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarkdownController;

// Route for the changelog (special case)
Route::get('/changelog', [MarkdownController::class, 'changelog'])->name('changelog');

// Generic route for all other markdown pages
Route::get('/{page}', [MarkdownController::class, 'show'])
    ->where('page', 'terms|policy|privacy')
    ->name('markdown.page');
