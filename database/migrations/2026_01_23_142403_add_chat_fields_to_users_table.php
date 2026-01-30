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
   Schema::table('users', function (Blueprint $table) {
        // Verifica se 'avatar' não existe antes de adicionar
        if (!Schema::hasColumn('users', 'avatar')) {
            $table->string('avatar')->nullable();
        }
        
        // Verifica se 'status' não existe antes de adicionar
        if (!Schema::hasColumn('users', 'status')) {
            $table->string('status')->default('offline');
        }
        });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
