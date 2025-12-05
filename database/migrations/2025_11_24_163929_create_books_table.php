<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    { 
         Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->text('title'); // Antes era string
            $table->text('isbn');// Antes era string
            $table->text('year')->nullable(); // Antes era integer
            $table->text('price')->nullable(); // Antes era decimal
            $table->text('bibliography')->nullable();
            $table->text('cover_image')->nullable(); // Antes era string
            $table->foreignId('publisher_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });

       
    
    }

    public function down(): void
    {
       
        Schema::dropIfExists('books');
    }
};
