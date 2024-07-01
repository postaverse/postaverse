<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Profile;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/u/{userId}', Profile::class);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/home', function () {
        return view('home');
    })->name('home');

});

require __DIR__.'/socialstream.php';
