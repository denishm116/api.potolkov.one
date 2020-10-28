<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title',  'meta_description', 'description'];
    protected $hidden = ['updated_at', 'deleted_at'];
    protected $appends = ['mainImage'];

    public function images()
    {
        return $this->morphMany('App\Models\Image', 'imageable');
    }

    public function catalogs()
    {
        return $this->morphedByMany('App\Models\Catalog', 'articable');
    }

    public function ceilings()
    {
        return $this->morphedByMany('App\Models\Ceiling', 'articable');
    }

    public function getMainImageAttribute() {
        return $this->images->where('main', 1)->first()->path ?? null;
    }
}
