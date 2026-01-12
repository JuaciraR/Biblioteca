<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cart;
use App\Mail\AbandonedCartMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class NotifyAbandonedCart extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'cart:notify-abandoned';

    /**
     * The console command description.
     */
    protected $description = 'Sends help notification to users with items in cart for more than 1 hour.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Define the time window (between 60 and 120 minutes ago)
        $from = Carbon::now()->subHours(2);
        $to = Carbon::now()->subHour();

        // Get carts updated in that window that have items
        $abandonedCarts = Cart::whereHas('items')
            ->whereBetween('updated_at', [$from, $to])
            ->get();

        $this->info("Checking for abandoned carts...");

        foreach ($abandonedCarts as $cart) {
            if ($cart->user) {
                Mail::to($cart->user->email)->send(new AbandonedCartMail($cart));
                
                // Update the timestamp so we don't send it again in the next hour
                $cart->touch();
                
                $this->info("Help email sent to: {$cart->user->email}");
            }
        }

        $this->info("Abandoned cart check finished.");
    }
}