<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Service;

class Servceimage extends Model
{
    protected $fillable = ['service_id', 'images'];


    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
