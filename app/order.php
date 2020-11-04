<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['order_number','status','created_at','user_id','total'];
}
