<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Request;
use App\Mail\DueDateReminderMail; // Mailable do Lembrete
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendDueReminders extends Command
{
    /**
     * O nome e a assinatura do comando da console.
     */
    protected $signature = 'emails:send-reminders';

    /**
     * A descrição do comando da console.
     */
    protected $description = 'Envia email reminders para livros com devolução agendada para amanhã.';

    /**
     * Executa o comando da console.
     */
    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        // REQUISITO: Encontra todas as requisições que estão 'Pending' ou 'Approved' e com due_date para amanhã.
        $requests = Request::with('user', 'book')
            ->whereIn('status', ['Pending', 'Approved']) // Livros que estão em posse do cliente
            ->whereDate('due_date', $tomorrow) // A data limite é amanhã
            ->get();

        $this->info("Encontradas {$requests->count()} requisições com devolução agendada para amanhã ({$tomorrow}).");

        foreach ($requests as $request) {
            try {
                // Envia o e-mail para o utilizador
                Mail::to($request->user->email)->send(new DueDateReminderMail($request));
            } catch (\Exception $e) {
                // Loga o erro, se o SMTP falhar
                $this->error("Falha ao enviar lembrete para {$request->user->email}. Erro: {$e->getMessage()}");
            }
        }

        // CORREÇÃO: Retorna o valor inteiro (0) em vez da constante estática, que é mais robusto.
        return 0; 
    }
}