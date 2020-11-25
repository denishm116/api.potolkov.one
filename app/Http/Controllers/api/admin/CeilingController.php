<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\Ceiling;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as Img;

class CeilingController extends Controller
{
    private $image;

    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    public function index()
    {
        return Ceiling::with('catalog')->get();
    }

    public function store(Request $request)
    {
        $slug = Str::slug($request->get('title'));
        $ceiling = new Ceiling;
        $ceiling->title = $request->get('title');
        $ceiling->slug = $slug;
        $ceiling->catalog_id = $request->get('catalog_id');
        $ceiling->description = $request->get('description');
        $ceiling->save();

        $files = $request->get('files');
        $this->image->saveImage($files, $ceiling, true);
        return $ceiling;
    }


    public function show($slug)
    {
        return Ceiling::with('images', 'catalog')->where('slug', $slug)->first();
    }


    public function update(Request $request, $id)
    {
        $catalog = Ceiling::findOrFail($id);
        $catalog->fill($request->except(['catalog_id']));
        $catalog->save();
        return $catalog;
    }

    public function destroy($ceiling)
    {
        $cat = Ceiling::where('slug', $ceiling)->first();

        try {
            if (isset($cat->images)) {
                foreach ($cat->images as $image) {
                    if (Storage::disk('local')->exists('/public/' . $image->path)) {
                        Storage::disk('local')->delete('/public/' . $image->path);
                    }
                }
                $cat->images()->delete();
            }
            $cat->delete();
            return Ceiling::all();

        } catch (Exception $e) {
            return $e;
        }
    }

    public function addImages(Request $request)
    {
        $entity = Ceiling::class;
        $this->image->addImages($request, $entity, true);
    }

    public function changeMainImage($id)
    {
        $this->image->changeMain($id);
    }

    public function deleteImage($id)
    {
        $this->image->deleteImage($id);
    }

}
