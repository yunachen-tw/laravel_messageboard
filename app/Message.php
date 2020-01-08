<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $primaryKey = 'message_id';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    public function board()
    {
        return $this->belongsTo(Board::class, 'board_id', 'board_id');
    }
    public function scores()
    {
        return $this->hasMany(Score::class, 'message_id', 'message_id');
    }
    public function deletable($user_id)
    {
        // true 表示可刪除, false 表示資格不符不可刪除
        if ($this->user_id == $user_id or $this->board->user_id == $user_id) {
            return true;
        }
        return false;
    }
}
