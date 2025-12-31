<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\PublishersTable;
use App\Livewire\BookDetail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BooksExport;
use App\Livewire\RequestsTable;
use App\Livewire\AdminReviewManagement;

Route::get('/', function () {
    return view('welcome');
});

// Áreas protegidas por login (Acesso por Admin E Cidadão)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rotas de Conteúdo Comum / Acessíveis a todos os logados
    
    Route::view('/books', 'books')->name('books');

  
    Route::get('/books/export', function () {
        return Excel::download(new BooksExport, 'books.xlsx');
    })->name('books.export');

    // Detalhe do Livro
    Route::get('/books/{book}', function (\App\Models\Book $book) {
        return view('books.show', ['book' => $book]);
    })->name('books.show');

    Route::get('/authors', function () {
        return view('authors.index');
    })->name('authors.index');

    Route::get('/requests', function () {
        return view('requests'); 
    })->name('requests');
    
   
    Route::get('/publishers', function () {
        return view('publishers', [
            'component' => PublishersTable::class
        ]);
    })->name('publishers');

    Route::get('/google-import', function () {
    return view('google-import');
})->name('book.google_import');

    
    // --- SOMENTE ADMIN (Rotas de Segurança/Criação) ---
    Route::middleware('admin')->group(function () {
        
        // CRIAÇÃO DE ADMINS RESTRITA
        Route::view('/admins/create', 'admins.create')->name('admins.create');

         Route::get('/admin/reviews', function () {
            return view('admin-review');
        })->name('admin.reviews');


    });
    // --- FIM SOMENTE ADMIN ---

});