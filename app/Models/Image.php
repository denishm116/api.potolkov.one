<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Img;

class Image extends Model
{
    public $timestamps = false;

    protected $fillable = ['imageable_type', 'imageable_id', 'path', 'main'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function imageable()
    {
        return $this->morphTo();
    }

    public function saveImage($files, $entity)
    {
        foreach ($files as $key => $file) {

            $path = 'images/' . uniqid() . '.jpg';
            $main = $file['main'];
            $resize = Img::make($file['image'])->resize(300, 200)->encode('jpg', 100);
            Storage::disk('public')->put($path, $resize);

            $image = new Image();
            $image->path = $path;
            $image->main = $main;
            $entity->images()->save($image);
        }
    }

    public function changeMain($id)
    {
        $new = self::find($id);
        $images = self::where('imageable_type', $new->imageable_type)->where('imageable_id', $new->imageable_id)->get();

        foreach ($images as $image) {
            $image->main = 0;
            $image->save();
        }

        $new->main = 1;
        $new->save();
    }

    public function deleteImage($id)
    {
        $image = self::findOrFail($id);

        Storage::disk('local')->delete('/public/' . $image->path);
        $image->delete();
        if (isset($image) && $image->main) {
            if ($last = self::where('imageable_type', $image->imageable_type)->where('imageable_id', $image->imageable_id)->first()) {
                $last->main = 1;
                $last->save();
            }

        }

    }

    public function addImages($request, $entityInit)
    {
        $entity = $entityInit::findOrFail($request->get('entity'))->with('images')->first();
        $main = 0;
        if (count($entity->images))
            $main = 1;
        foreach ($request->get('images') as $key => $file) {
            if ($key == 0 && !count($entity->images))
                $main = 1;
            else
                $main = 0;
            $path = 'images/' . uniqid() . '.jpg';
            $resize = Img::make($file['image'])->resize(300, 200)->encode('jpg', 100);
            Storage::disk('public')->put($path, $resize);
            $image = new Image();
            $image->path = $path;
            $image->main = $main;
            $entity->images()->save($image);
        }


    }
//    protected static function boot()
//    {
//        parent::boot();
//        static::deleting(function ($image) {
//            delete($image->path);
//        });
//    }
}
