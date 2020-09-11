<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Catalog extends Model
{
    use NodeTrait;
    protected $fillable = ['title', 'slug', 'parent_id', 'description'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    public $timestamps = false;

//    protected static function boot() {
//        parent::boot();
//        static::deleting(function($images) {
//            unlink($images->path);
//        });
//    }


    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function images()
    {
        return $this->morphMany('App\Models\Image', 'imageable');
    }
}
