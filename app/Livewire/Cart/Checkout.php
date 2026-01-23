<?php

namespace App\Livewire\Cart;
use App\Traits\Trackable;
use Livewire\Component;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class Checkout extends Component
{
    use Trackable;

    // Individual address properties
    public $street = '';
    public $city = '';
    public $zip_code = '';
    public $country = 'Portugal';
    public $total = 0;

    

    public function mount()
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart');
        }

        $this->total = $cart->items->sum(function ($item) {
            return (float) $item->book->price * $item->quantity;
        });
    }
    
    public function processCheckout(): mixed
    {
        // 1. Updated validation for separate fields
        $this->validate([
            'street'   => 'required|min:5',
            'city'     => 'required|min:2',
            'zip_code' => 'required|min:4',
        ]);

        /** @var \App\Models\Cart|null $cart */
        $cart = Cart::where('user_id', Auth::id())->first();

        if (!$cart || $cart->items->isEmpty()) {
            return null;
        }

        // Combine fields for the 'delivery_address' column
        $fullAddress = "{$this->street}, {$this->zip_code} {$this->city}, {$this->country}";

        // 2. Create the order with the combined address string
        $order = Order::create([
            'user_id'          => Auth::id(),
            'order_number'     => 'ORD-' . strtoupper(uniqid()),
            'total_amount'     => (float) $this->total,
            'status'           => 'pending',
            'delivery_address' => $fullAddress,
        ]);

        // 3. Create order items
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'book_id'  => $item->book_id,
                'quantity' => $item->quantity,
                'price'    => (float) $item->book->price,
            ]);
        }

        $this->logAudit(
            'Checkout', 
            $order->id, 
            "Created order {$order->order_number} with total of €{$this->total}"
        );

        // 4. Configure Stripe and Currency
        Stripe::setApiKey(config('services.stripe.secret'));

        $lineItems = $order->items->map(function ($item): array {
            return [
                'price_data' => [
                    'currency' => 'eur', // Currency set to Euro
                    'product_data' => [
                        'name' => $item->book->title,
                    ],
                    'unit_amount' => (int) round($item->price * 100),
                ],
                'quantity' => $item->quantity,
            ];
        })->toArray();

        // 5. Create checkout session
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => $lineItems,
            'mode'                 => 'payment',
            'success_url'          => route('checkout.success', $order->id),
            'cancel_url'           => route('checkout.cancel'),
        ]);

        // 6. Save Stripe session ID
        $order->update([
            'stripe_session_id' => $session->id,
        ]);

        // Use away() for cleaner external redirection
        return redirect()->away($session->url);
    }

    public function render()
    {
        return view('livewire.cart.checkout');
    }
}