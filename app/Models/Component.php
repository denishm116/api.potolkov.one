<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    protected $fillable = ['title', 'slug', 'catalog_id', 'description'];
    public $timestamps = false;
    protected $appends = ['mainImage'];

    public function images()
    {
        return $this->morphMany('App\Models\Image', 'imageable');
    }

    public function component_catalog()
    {
        return $this->belongsTo('App\Models\ComponentCatalog');
    }

    public function getMainImageAttribute()
    {
        return $this->images->where('main', 1)->first()->path ?? null;
    }
}
