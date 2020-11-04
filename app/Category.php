<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Comment;
use App\Rates;

class Category extends Model
{
    protected $table = 'categories';
    public $timestamps = false;
    protected $guarded = [];

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }// end relationship

    public function rates()
    {
        return $this->hasMany(Rates::class)->whereNull('parent_id');
    }
}
