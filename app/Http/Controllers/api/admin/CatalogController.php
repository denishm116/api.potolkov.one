<?php

namespace App\Http\Controllers\api\admin;

use App\Models\Catalog;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Http\Requests\CatalogRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class CatalogController extends Controller
{
    private $image;

    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    public function index()
    {
        return Catalog::defaultOrder()->withDepth()->get();
    }


    public function store(CatalogRequest $request)
    {
        $slug = Str::slug($request->get('title'));
        $catalog = new Catalog;
        $catalog->title = $request->get('title');
        $catalog->slug = $slug;
        $catalog->parent_id = $request->get('parent_id');
        $catalog->description = $request->get('description');
        $catalog->save();
        $files = $request->get('files');
        $this->image->saveImage($files, $catalog);
        return $catalog;
    }


    public function show($catalog)
    {

        return Catalog::with('images')->where('id', $catalog)->first();
    }

    public function up($catalog)
    {
        return Catalog::where('slug', $catalog)->first()->up();
    }


    public function down($catalog)
    {

        return Catalog::where('slug', $catalog)->first()->down();
    }

    public function update(Request $request, $catalog)
    {

        $catalog = Catalog::findOrFail($catalog);
        $catalog->fill($request->except(['catalog_id']));
        $catalog->save();
        return $catalog;
    }

    public function destroy($catalog)
    {
        $cat = Catalog::where('slug', $catalog)->first();
        try {
            foreach ($cat->images as $image) {
                if (Storage::disk('local')->exists('/public/' . $image->path)) {
                    Storage::disk('local')->delete('/public/' . $image->path);
                }
            }
            $cat->images()->delete();
            $cat->delete();
            return Catalog::all();
        } catch (Exception $e) {
            return $e;
        }
    }

    public function addImages(Request $request)
    {
        $entity = Catalog::class;
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
