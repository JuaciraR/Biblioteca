<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailabilityAlert extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser preenchidos em massa.
     * user_id: ID do Cidadão que quer o alerta.
     * book_id: ID do Livro que está indisponível.
     */
    protected $fillable = [
        'user_id',
        'book_id'
    ];

    /**
     * Relação: O alerta pertence a um Utilizador.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relação: O alerta refere-se a um Livro.
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}