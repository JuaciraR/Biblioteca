<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\AuditLog;
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

        AuditLog::logAction(
            'Checkout', 
            $order->id, 
            "Payment successful for Order #{$order->id}. Status updated to PAID and cart cleared."
        );
        
        return view('checkout-success', [
            'order' => $order
        ]);
    }

    /**
     * Handles payment cancellation.
     */
    public function cancel()
    {
        AuditLog::logAction(
            'Checkout', 
            Auth::id(), 
            "User cancelled the payment process at Stripe checkout."
        );
        return view('checkout-cancel');
    }
}