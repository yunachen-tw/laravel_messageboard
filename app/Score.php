<?php

namespace App;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $primaryKey = ['message_id', 'user_id'];
    public $incrementing = false;

    protected $fillable = [
        'message_id', 'user_id', 'score', 'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    public function message()
    {
        return $this->belongsTo(Message::class, 'message_id', 'message_id');
    }
    protected function setKeysForSaveQuery(Builder $query)
    {
        $query->where('user_id', '=', $this->getAttribute('user_id'))
            ->where('message_id', '=', $this->getAttribute('message_id'));
        return $query;
    }
}
