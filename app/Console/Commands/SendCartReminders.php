<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cart;
use App\Mail\AbandonedCartMail;
use Illuminate\Support\Facades\Mail;

class SendCartReminders extends Command
{
    // O nome do comando no terminal ou scheduler
    protected $signature = 'app:send-cart-reminders';

    // Descrição do que o comando faz
    protected $description = 'Send an email to users who abandoned their cart for more than 1 hour';

    public function handle()
    {
        //  Procurar carrinhos com itens, sem email enviado, e parados há +1 hora
        $carts = Cart::whereHas('items')
            ->where('updated_at', '<=', now()->subHour())
            ->where('reminder_sent', false) 
            ->get();

        if ($carts->isEmpty()) {
            $this->info('No abandoned carts found at the moment.');
            return;
        }

        foreach ($carts as $cart) {
            
            Mail::to($cart->user->email)->send(new AbandonedCartMail($cart->user));

            //  Marca como enviado para não repetir a notificação
            $cart->update(['reminder_sent' => true]);
            
            $this->info("Reminder sent to: {$cart->user->email}");
        }

        $this->info('Process completed successfully.');
    }
}