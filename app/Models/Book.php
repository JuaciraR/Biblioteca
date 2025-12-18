<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    
    const LOAN_DAYS = 5; 

    protected $fillable = [
        'title',
        'isbn',
        'year',
        'price',
        'publisher_id',
        'bibliography',
        'cover_image',
    ];

    protected $casts = [
        'title' => 'encrypted',
        'isbn' => 'encrypted',
        'year' => 'encrypted',
        'price' => 'encrypted',
        'bibliography' => 'encrypted',
        'cover_image' => 'encrypted',
    ];

    // Relação com Publisher
    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    // Relação muitos-para-muitos com Authors
    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }

    public function requests()
    {
    return $this->hasMany(Request::class);
    }


     public function isAvailableForRequest(): bool
    {
        // REQUISITO: Verificar se o livro está num processo de requisição.
        // O livro está indisponível se houver alguma requisição com status Pending ou Approved.
        return $this->requests()
            ->whereIn('status', ['Pending', 'Approved'])
            ->doesntExist();
    }

    public function reviews()
{
    return $this->hasMany(Review::class)->latest();
}

public function averageRating()
{
    return $this->reviews()->avg('rating') ?? 0;
}
}
