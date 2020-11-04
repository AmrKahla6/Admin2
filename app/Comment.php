<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\member;

class Comment extends Model
{
    protected $guarded = [];

    public function member()
    {
        return $this->belongsTo(member::class , 'member_id');
    }


    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
