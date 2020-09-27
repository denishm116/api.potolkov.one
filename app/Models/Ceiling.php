<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ceiling extends Model
{
    protected $fillable = ['title', 'slug', 'catalog_id', 'description'];
    public $timestamps = false;

    public function images()
    {
        return $this->morphMany('App\Models\Image', 'imageable');
    }

    public function catalog()
    {
        return $this->belongsTo('App\Models\Catalog');
    }
}
