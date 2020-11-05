<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Citiesimage;

class City extends Model
{
    protected $table = 'cities';
    public $timestamps = false;
    protected $guarded = [];

    public function cityimages()
    {
        return $this->belongsToMany(Citiesimage::class);
    }
}
