<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\PublishersTable;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BooksExport;



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

Route::get('/publishers', function () {
    return view('publishers', [
        'component' => PublishersTable::class
    ]);
})->name('publishers');

Route::get('/books/export', function () {
    return Excel::download(new BooksExport, 'books.xlsx');
})->middleware(['auth'])->name('books.export');






