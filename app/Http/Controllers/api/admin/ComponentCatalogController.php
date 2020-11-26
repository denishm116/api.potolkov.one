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

        $this->image->saveImage($files, $catalog, true);
        return $catalog;
    }

    public function show($component_catalog)
    {
        return ComponentCatalog::with('images')->where('id', $component_catalog)->first();
    }


    public function up($component_catalog)
    {
        return ComponentCatalog::where('slug', $component_catalog)->first()->up();
    }


    public function down($component_catalog)
    {
        return ComponentCatalog::where('slug', $component_catalog)->first()->down();
    }


    public function update(Request $request, $id)
    {
        $component_catalog = ComponentCatalog::findOrFail($id);
        $component_catalog->fill($request->except(['lightning_id']));
        $component_catalog->save();
        return $component_catalog;
    }

    public function destroy($component_catalog)
    {
        $cat = ComponentCatalog::where('slug', $component_catalog)->first();

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

    public function addImages(Request $request)
    {
        $entity = ComponentCatalog::class;
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
