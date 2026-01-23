<?php

use App\Models\User;
use App\Models\Book;
use App\Models\Request;
use App\Models\Publisher;
use Livewire\Livewire;
use Illuminate\Support\Facades\Auth;

/**
 * CHALLENGE 1: User can create a book request correctly.
 * Validates if the request is stored in the database with 'Pending' status.
 */
test('user can create a book request correctly', function () {
    $user = User::factory()->create(['role' => 'Cidadao']);
    $publisher = Publisher::factory()->create();
    $book = Book::factory()->create([
        'publisher_id' => $publisher->id,
        'stock' => 5
    ]);

    $this->actingAs($user);

    Livewire::test('book-request-button', ['book' => $book])
        ->call('requestBook')
        ->assertDispatched('request-notification');

    // Assert the record exists in the 'requests' table
    $this->assertDatabaseHas('requests', [
        'user_id' => $user->id,
        'book_id' => $book->id,
        'status'  => 'Pending'
    ]);
});

/**
 * CHALLENGE 2: Request cannot be created without a valid book.
 * Checks if the system handles empty or invalid book models gracefully.
 */
test('request cannot be created without a valid book', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    // Testing with an empty Book model to trigger error handling
    Livewire::test('book-request-button', ['book' => new Book()])
        ->call('requestBook')
        ->assertDispatched('request-notification'); 
});

/**
 * CHALLENGE 3: Confirms if a user (Admin) can return a book.
 * Updates status to 'Received' and sets the reception timestamp.
 */
test('confirms if a user can return a book', function () {
    $admin = User::factory()->create(['role' => 'Admin']);
    $user = User::factory()->create(['role' => 'Cidadao']);
    $publisher = Publisher::factory()->create();
    $book = Book::factory()->create(['publisher_id' => $publisher->id]);
    
    $request = Request::create([
        'user_id' => $user->id,
        'book_id' => $book->id,
        'status' => 'Approved',
        'request_number' => 'REQ-' . uniqid(),
        'requested_at' => now(),
    ]);

    // Simulamos o Admin logado (conforme o teu componente exige)
    $this->actingAs($admin);

    Livewire::test('requests-table') 
        ->call('confirmReception', $request->id);

    // ESSENCIAL: Atualizar a instância que está na memória do teste
    $request->refresh(); 

    expect($request->status)->toBe('Received');
    $this->assertNotNull($request->received_at);
});
/**
 * CHALLENGE 4: Guarantees a user only sees their own requests.
 * Privacy check to ensure data isolation between different users.
 */
test('guarantees a user only sees their own requests', function () {
    $userA = User::factory()->create(['role' => 'Cidadao']);
    $userB = User::factory()->create(['role' => 'Cidadao']);
    $publisher = Publisher::factory()->create();
    $book = Book::factory()->create(['publisher_id' => $publisher->id]);

    // Requisição para o User A
    Request::create([
        'user_id' => $userA->id,
        'book_id' => $book->id,
        'status' => 'Pending',
        'request_number' => 'REQ-A',
        'requested_at' => now(),
    ]);

    // Requisição para o User B
    Request::create([
        'user_id' => $userB->id,
        'book_id' => $book->id,
        'status' => 'Pending',
        'request_number' => 'REQ-B',
        'requested_at' => now(),
    ]);

    $this->actingAs($userA);

    // O teste agora valida se o componente filtrou corretamente no render()
    Livewire::test('requests-table')
        ->assertViewHas('requests', function ($requests) use ($userA) {
            // Garante que APENAS 1 registo é retornado e pertence ao User A
            return $requests->count() === 1 && $requests->first()->user_id === $userA->id;
        });
});
/**
 * CHALLENGE 5: Stock validation during book order.
 * Ensures users cannot request books that are out of stock.
 */
test('cannot request a book without available stock', function () {
    $user = User::factory()->create(['role' => 'Cidadao']);
    $publisher = Publisher::factory()->create();
    $book = Book::factory()->create([
        'publisher_id' => $publisher->id,
        'stock' => 0
    ]);

    $this->actingAs($user);

    // Assert that the system notifies the user about the lack of stock
    Livewire::test('book-request-button', ['book' => $book])
        ->call('requestBook')
        ->assertDispatched('request-notification', function($name, $params) {
            return str_contains($params['message'], 'stock');
        });
});