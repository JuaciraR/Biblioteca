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
       Schema::create('chat_rooms', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('avatar')->nullable();
        $table->foreignId('owner_id')->constrained('users')->onDelete('cascade'); // Who creates (Admin)
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_rooms');
    }
};
