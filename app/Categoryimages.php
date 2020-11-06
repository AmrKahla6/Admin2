<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoryimages extends Model
{
    public $timestamps = false;
    protected $fillable = ['category_id','image'];
}
