<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define os comandos de agendamento do Artisan para a aplicação.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     */
    protected function schedule(Schedule $schedule): void
    {
        // REQUISITO: Enviar Reminder no dia anterior à entrega
        // Agenda o comando 'emails:send-reminders' para rodar todos os dias às 08:00
        $schedule->command('emails:send-reminders')->dailyAt('08:00'); 
    }

    /**
     * Regista os comandos da aplicação.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}