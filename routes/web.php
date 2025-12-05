<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\PublishersTable;
use App\Livewire\BookDetail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BooksExport;
use App\Livewire\RequestsTable;

Route::get('/', function () {
    return view('welcome');
});

// Áreas protegidas por login
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Rotas acessíveis a TODOS os usuários logados (Admin e Cidadão)
    // Colocamos aqui as rotas que ambos usam (Catálogo, Detalhe, Requisições, Autores)
    
    // Rota do Catálogo principal (que carrega a BooksTable)
    Route::view('/books', 'books')->name('books');

    // REQUISITO: Rota para o Detalhe do Livro (Histórico) - Chamar a view container
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
        


    // --- SOMENTE ADMIN (TODAS ESTAS ROTAS ESTÃO PROTEGIDAS PELO MIDDLEWARE ADMIN) ---
    Route::middleware('admin')->group(function () {
        // Export Excel
        Route::get('/books/export', function () {
            return Excel::download(new BooksExport, 'books.xlsx');
        })->name('books.export');

        // Publishers
        Route::get('/publishers', function () {
            return view('publishers', [
                'component' => PublishersTable::class
            ]);
        })->name('publishers');
        
        // CRIAÇÃO DE ADMINS RESTRITA
        Route::view('/admins/create', 'admins.create')->name('admins.create');

    });
    // --- FIM SOMENTE ADMIN ---

});



