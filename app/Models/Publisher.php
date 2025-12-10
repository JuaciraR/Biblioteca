<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'name', 'logo'];

    protected $casts = [
        'name' => 'encrypted',
        'logo' => 'encrypted',
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}

