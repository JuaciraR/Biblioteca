<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained(); // Remetente
        $table->foreignId('chat_room_id')->nullable()->constrained(); // Se for nulo, é mensagem direta
        $table->foreignId('receiver_id')->nullable()->constrained('users'); // Para mensagens diretas
        $table->text('content');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
