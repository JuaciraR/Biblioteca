<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\PublishersTable;
use App\Livewire\BookDetail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BooksExport;
use App\Livewire\RequestsTable;
use App\Livewire\Cart\CartPage;
use App\Livewire\Cart\Checkout;
use App\Livewire\AdminReviewManagement;
use App\Http\Controllers\CheckoutController;
use App\Livewire\Admin\OrderList;
use App\Http\Controllers\AuditLogController;
use App\Livewire\Chat\RoomView;


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
    Route::get('/basket', CartPage::class)->name('cart');
Route::get('/checkout', Checkout::class)->name('checkout');
   
    Route::get('/publishers', function () {
        return view('publishers', [
            'component' => PublishersTable::class
        ]);
    })->name('publishers');

    Route::get('/google-import', function () {
    return view('google-import');
})->name('book.google_import');


Route::get('/basket', function () {
    return view('cart-page');
})->name('cart');

// Checkout Route
Route::get('/checkout', function () {
    return view('checkout-page');
})->name('checkout');

// Payment Flow Routes
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
    
    // --- SOMENTE ADMIN (Rotas de Segurança/Criação) ---
    Route::middleware('admin')->group(function () {
        
        // CRIAÇÃO DE ADMINS RESTRITA
        Route::view('/admins/create', 'admins.create')->name('admins.create');

         Route::get('/admin/reviews', function () {
            return view('admin-review');
        })->name('admin.reviews');

    Route::get('/admin/management', function () {
        return view('admin-orders'); 
    })->name('admin.management');

   
    Route::get('/admin/orders', function () {
        return view('order-management'); 
    })->name('admin.orders');

    });

Route::get('/logs', [AuditLogController::class, 'index'])->name('logs.index');

Route::get('/chat', function () {
        return view('chat-layout'); 
    })->name('chat.index');

    // Rota para Salas Específicas
    Route::get('/chat/room/{room}', RoomView::class)->name('chat.room');

    // Rota para Mensagens Diretas
    Route::get('/chat/user/{user}', RoomView::class)->name('chat.user');

});