<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\ComponentCatalog;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ComponentCatalogController extends Controller
{
    private $image;

    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    public function index()
    {
        return ComponentCatalog::defaultOrder()->withDepth()->get();

    }


    public function store(Request $request)
    {
        $slug = Str::slug($request->get('title'));
        $catalog = new ComponentCatalog;
        $catalog->title = $request->get('title');
        $catalog->slug = $slug;
        $catalog->parent_id = $request->get('parent_id');
        $catalog->description = $request->get('description');
        $catalog->save();

        $files = $request->get('files');

        $this->image->saveImage($files, $catalog);
        return $catalog;
    }

    public function show($lightning_catalog)
    {
        return ComponentCatalog::with('images')->where('slug', $lightning_catalog)->get();
    }


    public function up($lightning_catalog)
    {
        return ComponentCatalog::where('slug', $lightning_catalog)->first()->up();
    }


    public function down($catalog)
    {
        return ComponentCatalog::where('slug', $catalog)->first()->down();
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($lightning_catalog)
    {
        $cat = ComponentCatalog::where('slug', $lightning_catalog)->first();

        try {
            foreach ($cat->images as $image) {
                if (Storage::disk('local')->exists('/public/' . $image->path)) {
                    Storage::disk('local')->delete('/public/' . $image->path);
                }
            }
            $cat->images()->delete();
            $cat->delete();
            return $cat;

        } catch (Exception $e) {
            return $e;
        }
    }
}
