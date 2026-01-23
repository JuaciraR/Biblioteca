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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();

            // Campo para a numeração sequencial das requisições (Requisito)
            $table->string('request_number', 50)->nullable()->unique();


            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');

            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Received'])
                ->default('Pending');
                
            // Data em que a requisição foi feita (Requisito: dia da mesma)
            $table->timestamp('requested_at')->nullable(); 
            
            // Data de fim prevista (Requisito: sempre 5 dias após a requisição)
            $table->timestamp('due_date')->nullable();

            // Data real da boa receção/devolução do livro (Requisito: Confirmar Receção)
            $table->timestamp('received_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};