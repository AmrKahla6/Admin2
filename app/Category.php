<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Comment;
use App\Service;

use willvincent\Rateable\Rateable;

class Category extends Model
{
    use Rateable;

    protected $table = 'categories';
    public $timestamps = false;
    protected $guarded = [];
 /**
     * The has Many Relationship
     *
     * @var array
 */
    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }// end relationship

    public function services()
    {
        return $this->belongsToMany(Service::class, 'categories_services');
    }

}
