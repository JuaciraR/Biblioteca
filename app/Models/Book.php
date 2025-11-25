<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

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
    ];

    // Relação com Publisher
    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    // Relação muitos-para-muitos com Authors
  //  public function authors()
  //  {
    //    return $this->belongsToMany(Author::class);
   // }
}
