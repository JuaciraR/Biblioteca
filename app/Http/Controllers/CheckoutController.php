<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Handles the successful redirection from Stripe.
     */
    public function success(Request $request, $orderId)
    {
        //  Procura a encomenda garantindo que pertence ao utilizador
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        
        $order->update(['status' => 'paid']);

        
        $cart = Cart::where('user_id', Auth::id())->first();
        if ($cart) {
            $cart->items()->delete();
        }

        
        return view('checkout-success', [
            'order' => $order
        ]);
    }

    /**
     * Handles payment cancellation.
     */
    public function cancel()
    {
        
        return view('checkout-cancel');
    }
}