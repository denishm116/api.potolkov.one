<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lightning extends Model
{
    protected $fillable = ['title', 'slug', 'catalog_id', 'description'];
    public $timestamps = false;
    protected $appends = ['mainImage'];

    public function images()
    {
        return $this->morphMany('App\Models\Image', 'imageable');
    }

    public function lightning_catalog()
    {
        return $this->belongsTo('App\Models\LightningCatalog');
    }

    public function getMainImageAttribute() {
        return $this->images->where('main', 1)->first()->path ?? null;
    }


}
