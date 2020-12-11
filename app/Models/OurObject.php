<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class OurObject extends Model
{
    protected $fillable = ['title',  'address', 'square', 'description', 'price', 'landing'];
    protected $hidden = ['updated_at', 'deleted_at'];
    protected $appends = ['mainImage'];

    public function images()
    {
        return $this->morphMany('App\Models\Image', 'imageable');
    }

    public function catalogs()
    {
        return $this->morphedByMany('App\Models\Catalog', 'presenter');
    }

    public function ceilings()
    {
        return $this->morphedByMany('App\Models\Ceiling', 'presenter');
    }

    public function getMainImageAttribute() {
        return $this->images->where('main', 1)->first()->path ?? null;
    }

}
