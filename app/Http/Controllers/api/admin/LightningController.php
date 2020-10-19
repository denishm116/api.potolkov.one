<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Lightning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LightningController extends Controller
{
    private $image;
    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    public function index()
    {
        return Lightning::all();
    }

    public function store(Request $request)
    {
        dd($request->input());
        $slug = Str::slug($request->get('title'));
        $lightning = new Lightning;
        $lightning->title = $request->get('title');
        $lightning->slug = $slug;
        $lightning->lightning_catalog_id = $request->get('catalog_id');
        $lightning->description = $request->get('description');
        $lightning->save();

        $files = $request->get('files');
        $this->image->saveImage($files, $lightning);
        return $lightning;
    }


    public function show($slug)
    {
        return Lightning::with('images')->where('slug', $slug)->first();
    }


    public function update(Request $request, $id)
    {
        $lightning = Lightning::findOrFail($id);
        $lightning->fill($request->except(['catalog_id']));
        $lightning->save();
        return $lightning;
    }

    public function destroy($lightning)
    {
        $cat = Lightning::where('slug',$lightning )->first();

        try {
            foreach ($cat->images as $image) {
                if (Storage::disk('local')->exists('/public/' . $image->path)) {
                    Storage::disk('local')->delete('/public/' . $image->path);
                }
            }
            $cat->images()->delete();
            $cat->delete();
            return Lightning::all();

        } catch (Exception $e) {
            return $e;
        }
    }

    public function addImages(Request $request)
    {
        $entity = Lightning::class;
        $this->image->addImages($request, $entity);
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
