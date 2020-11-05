<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Servceimage;
use App\Category;

class Service extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function servceimages()
    {
        return $this->belongsToMany(Servceimage::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categories_services');
    }


}
