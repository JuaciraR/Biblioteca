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
            $table->string('title');
            $table->string('isbn')->unique();
            $table->integer('year')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->text('bibliography')->nullable();
            $table->string('cover_image')->nullable();
            $table->foreignId('publisher_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });

       
    
    }

    public function down(): void
    {
       
        Schema::dropIfExists('books');
    }
};
