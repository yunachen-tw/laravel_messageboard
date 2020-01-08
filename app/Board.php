<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    protected $primaryKey = 'board_id';
    protected $fillable = ['title', 'describe', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    public function messages()
    {
        return $this->hasMany(Message::class, 'board_id', 'board_id');
    }
}
