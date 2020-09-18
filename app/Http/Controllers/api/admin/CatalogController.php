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
use Intervention\Image\Facades\Image as Img;

class CatalogController extends Controller
{

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
        foreach ($files as $key => $file) {
            $path = 'images/' . uniqid() . '.jpg';
            $resize = Img::make($file)->resize(300, 200)->encode('jpg',100);;
            Storage::disk('public')->put( $path, $resize);
            $image = new Image;
            $image->path = $path;
            $catalog->images()->save($image);
        }
        return $catalog;
    }


    public function show($catalog)
    {
        return Catalog::with('images')->where('slug', $catalog)->get();
    }

    public function up($catalog)
    {
        return Catalog::where('slug', $catalog)->first()->up();
    }


    public function down($catalog)
    {
        return Catalog::where('slug', $catalog)->first()->down();
    }

    public function update(CatalogRequest $request, Catalog $catalog)
    {
        $catalog = Catalog::findOrFail($catalog);
        $catalog->fill($request->except(['catalog_id']));
        $catalog->save();
        return $catalog;
    }


    public function destroy($catalog)
    {
        $cat = Catalog::where('slug', $catalog)->first();
                foreach ( $cat->images as $image) {
            unlink(public_path($image->path));
        }

        $cat->images()->delete();

        $cat->delete();
    }
}
