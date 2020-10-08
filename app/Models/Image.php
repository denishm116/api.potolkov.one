<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Img;

class Image extends Model
{
    public $timestamps = false;

    protected $fillable = ['imageable_type', 'imageable_id', 'path', 'main'];

    public function imageable()
    {
        return $this->morphTo();
    }

    public function saveImage($files, $catalog)
    {
        foreach ($files as $key => $file) {

            $path = 'images/' . uniqid() . '.jpg';
            $main = $file['main'];
            $resize = Img::make($file['image'])->resize(300, 200)->encode('jpg', 100);
            Storage::disk('public')->put($path, $resize);

            $image = new Image;
            $image->path = $path;
            $image->main = $main;
            $catalog->images()->save($image);
        }
    }

    protected static function boot() {
        parent::boot();
        static::deleting(function($image) {
            delete($image->path);
        });
    }
}
