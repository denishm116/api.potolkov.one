<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $timestamps = false;

    protected $fillable = ['imageable_type', 'imageable_id', 'path', 'main'];

    public function imageable()
    {
        return $this->morphTo();
    }

    protected static function boot() {
        parent::boot();
        static::deleting(function($image) {
            delete($image->path);
        });
    }
}
