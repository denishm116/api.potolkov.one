<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Component;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ComponentController extends Controller
{
    private $image;
    public function __construct(Image $image)
    {
        $this->image = $image;
    }
    public function index()
    {
        return Component::all();
    }


    public function store(Request $request)
    {
        $slug = Str::slug($request->get('title'));
        $component = new Component;
        $component->title = $request->get('title');
        $component->slug = $slug;
        $component->component_catalog_id = $request->get('catalog_id');
        $component->description = $request->get('description');
        $component->save();

        $files = $request->get('files');
        $this->image->saveImage($files, $component);
        return $component;
    }


    public function show($slug)
    {
        return Component::with('images')->where('slug', $slug)->get();
    }

    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($component)
    {
        $cat = Component::where('slug',$component)->first();

        try {
            foreach ($cat->images as $image) {
                if (Storage::disk('local')->exists('/public/' . $image->path)) {
                    Storage::disk('local')->delete('/public/' . $image->path);
                }
            }
            $cat->images()->delete();
            $cat->delete();
            return Component::all();

        } catch (Exception $e) {
            return $e;
        }
    }
}
