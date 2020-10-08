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

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function images()
    {
        return $this->morphMany('App\Models\Image', 'imageable');
    }

    public function component()
    {
        return $this->hasOne('App\Models\Component', 'component_catalog_id');
    }
}