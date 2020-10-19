<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class OurObject extends Model
{
    protected $fillable = ['title',  'address', 'square', 'description', 'price'];
    protected $hidden = ['updated_at', 'deleted_at'];

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
}
