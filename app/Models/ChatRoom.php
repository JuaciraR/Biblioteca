<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    protected $fillable = [
        'name',
        'owner_id',
    ];
    public function users()
{
    return $this->belongsToMany(User::class);
}

public function owner()
{
    return $this->belongsTo(User::class, 'owner_id');
}
public function messages()
{
    return $this->hasMany(Message::class);
}
}
