<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;

use App\Models\Image;
use App\Models\LightningCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LightningCatalogController extends Controller
{
    private $image;

    public function __construct(Image $image)
    {
        $this->image = $image;
    }
    public function index()
    {
        return LightningCatalog::defaultOrder()->withDepth()->get();
    }

    public function store(Request $request)
    {
        $slug = Str::slug($request->get('title'));
        $catalog = new LightningCatalog;
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
        return LightningCatalog::with('images')->where('slug', $lightning_catalog)->first();
    }


    public function up($lightning_catalog)
    {
        return LightningCatalog::where('slug', $lightning_catalog)->first()->up();
    }


    public function down($catalog)
    {
        return LightningCatalog::where('slug', $catalog)->first()->down();
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($lightning_catalog)
    {
        $cat = LightningCatalog::where('slug', $lightning_catalog)->first();

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
        $entity = LightningCatalog::class;
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
