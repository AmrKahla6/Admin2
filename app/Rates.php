<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\member;

class Rates extends Model
{
    protected $guarded = [];

    public function member()
    {
        return $this->belongsTo(member::class);
    }
}
