<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class ComponentCatalog extends Model
{
    use NodeTrait;
    protected $fillable = ['title', 'slug', 'parent_id', 'description'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    public $timestamps = false;
    protected $appends = ['mainImage'];

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function images()
    {
        return $this->morphMany('App\Models\Image', 'imageable');
    }

    public function components()
    {
        return $this->hasMany('App\Models\Component', 'component_catalog_id');
    }

    public function getMainImageAttribute()
    {
        return $this->images->where('main', 1)->first()->path ?? null;
    }
}
