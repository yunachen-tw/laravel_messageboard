<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    public function boards()
    {
        return $this->hasMany(Board::class, 'user_id', 'user_id');
    }
    public function messages()
    {
        return $this->hasMany(Message::class, 'user_id', 'user_id');
    }
    public function scores()
    {
        return $this->hasMany(Score::class, 'user_id', 'user_id');
    }
}
