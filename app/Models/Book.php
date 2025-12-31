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
        public function activeReviews()
        {
        return $this->hasMany(Review::class)->where('status', 'active')->latest();
        }


        public function reviews()
        {
            return $this->hasMany(Review::class)->latest();
        }

     
           public function averageRating()
        {
             return (float) ($this->activeReviews()->avg('rating') ?? 0);
         }
public function getRelatedBooks($limit = 5)
    {
        // 1. Unimos o título e a bibliografia para análise
        $content = strtolower($this->title . ' ' . $this->bibliography);
        
        // 2. Extraímos as palavras (removendo pontuação)
        $allWords = str_word_count($content, 1);

        // 3. Lista de "Stop Words" (palavras comuns que devem ser ignoradas)
        $stopWords = [
            'para', 'com', 'uma', 'pela', 'sobre', 'este', 'esta', 'livro', 'historia',
            'the', 'and', 'with', 'from', 'this', 'that', 'book', 'story'
        ];

        // 4. Filtramos palavras com mais de 3 letras que não sejam stop-words
        $keywords = array_filter($allWords, function($word) use ($stopWords) {
            return strlen($word) > 3 && !in_array($word, $stopWords);
        });

        // Removemos duplicados e limitamos para performance
        $keywords = array_slice(array_unique($keywords), 0, 10);

        // Tenta encontrar por similaridade
        $related = self::where('id', '!=', $this->id)
            ->where(function ($query) use ($keywords) {
                foreach ($keywords as $word) {
                    $query->orWhere('title', 'LIKE', '%' . $word . '%')
                          ->orWhere('bibliography', 'LIKE', '%' . $word . '%');
                }
            })
            ->take($limit)
            ->get();

        // FALLBACK 1: Se não encontrou nada por keywords, sugere livros da mesma Editora
        if ($related->isEmpty()) {
            $related = self::where('id', '!=', $this->id)
                ->where('publisher_id', $this->publisher_id)
                ->latest()
                ->take($limit)
                ->get();
        }

        // FALLBACK 2: Se ainda assim não houver nada, mostra os livros mais recentes do catálogo
        if ($related->isEmpty()) {
            $related = self::where('id', '!=', $this->id)
                ->latest()
                ->take($limit)
                ->get();
        }

        return $related;
    }
}
