<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    /** @use HasFactory<\Database\Factories\RequestFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'status',
        // NOVOS CAMPOS ADICIONADOS:
        'request_number',
        'requested_at',
        'due_date',
        'received_at',
    ];
    
    // Casts para garantir que as datas sejam objetos Carbon
    protected $casts = [
        'requested_at' => 'datetime',
        'due_date' => 'datetime',
        'received_at' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
