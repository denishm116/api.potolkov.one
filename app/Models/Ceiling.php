<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ceiling extends Model
{
    protected $fillable = ['title', 'slug', 'catalog_id', 'description'];
    public $timestamps = false;
    protected $appends = ['mainImage'];

    public function images()
    {
        return $this->morphMany('App\Models\Image', 'imageable');
    }

    public function catalog()
    {
        return $this->belongsTo('App\Models\Catalog');
    }
    public function our_objects()
    {
        return $this->morphToMany('App\Models\OurObject', 'presenter');
    }

    public function articles()
    {
        return $this->morphToMany('App\Models\Articles', 'article');
    }
    public function getMainImageAttribute() {
        return $this->images->where('main', 1)->first()->path ?? null;
    }
}
