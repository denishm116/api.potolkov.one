<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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

    public function saveImage($files, $entity, $thumbs = false)
    {
        return DB::transaction(function () use ($files, $entity, $thumbs) {
            foreach ($files as $key => $file) {
                $this->imageSaver($file, $file['main'], $file['title'] ?? false, $file['description'] ?? false, $entity, $thumbs);
            }
        });

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
        if ($image->thumb)
            Storage::disk('local')->delete('/public/' . $image->thumb);

        $image->delete();

        if (isset($image) && $image->main) {
            if ($last = self::where('imageable_type', $image->imageable_type)->where('imageable_id', $image->imageable_id)->first()) {
                $last->main = 1;
                $last->save();
            }
        }
    }

    public function addImages($request, $entityInit, $thumbs = false)
    {
        $entity = $entityInit::with('images')->where('id', $request->get('entity'))->first();
        $main = 0;
        if (count($entity->images))
            $main = 1;
        return DB::transaction(function () use ($request, $entity, $thumbs) {
            foreach ($request->get('images') as $key => $file) {

                if ($key == 0 && !count($entity->images))
                    $main = 1;
                else
                    $main = 0;
                $this->imageSaver($file, $main, $file['title'] ?? null, $file['description'] ?? null, $entity, true );
            }
        });
    }

    private function imageSaver($file, $main, $title, $description, $entity, $thumbs)
    {
        $p = uniqid();
        $path = 'images/' . $p . '.jpg';
        $thumbPath = 'thumbs/' . $p . '.jpg';
        $resize = Img::make($file['image'])->encode('jpg', 100);
        Storage::disk('public')->put($path, $resize);
        $image = new Image();
        $image->path = $path;
        $image->main = $main;

        if ($title && $description) {
            $image->title = $title;
            $image->description = $description;
        }

        if ($thumbs) {
            $resizeThumb = Img::make($file['image'])->resize(100, 68)->encode('jpg', 90);
            Storage::disk('public')->put($thumbPath, $resizeThumb);
            $image->thumb = $thumbPath;
        }
        $entity->images()->save($image);
    }

}
