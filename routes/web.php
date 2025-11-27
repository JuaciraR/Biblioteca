<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

  
  Route::view('/books', 'books')->name('books');




});

  Route::get('/authors', function () {
    return view('authors.index');
})->middleware(['auth'])->name('authors.index');





