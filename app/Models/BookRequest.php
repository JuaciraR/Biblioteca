<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookRequest extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'book_id', 'request_number', 'status', 'requested_at', 'due_date', 'received_at'];

    // Relacionamento com o Utilizador
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Relacionamento com o Livro
    public function book() {
        return $this->belongsTo(Book::class);
    }
}