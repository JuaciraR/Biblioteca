<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id',
        'rating',
        'comment',
    ];

    /**
     * Relação com o Livro (Book)
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Relação com o Utilizador (User)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}