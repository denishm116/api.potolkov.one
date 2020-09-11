<?php

namespace App\Http\Controllers\api;

use App\Models\Catalog;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Http\Requests\CatalogRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image as Img;

class CatalogController extends Controller
{

    public function index()
    {
//        return Catalog::all();
//        dd(Catalog::with('children')->where('parent_id', NULL)->get());
        return Catalog::defaultOrder()->withDepth()->get();
//        return Catalog::with('children')->where('parent_id', NULL)->get();
    }


    public function store(CatalogRequest $request)
    {


//        $validator = Validator::make($request->category, [
//            'title' => 'required|string|min:2',
//            'parent_id' => 'integer',
//            'description' => 'string|min:2'
//        ]);
//        if ($validator->fails()) {
//            return response()->json(
//                ['success' => false,
//                    'errors' => $validator->errors()
//                ], 422);
//        }
        $slug = Str::slug($request->get('title'));

        $catalog = new Catalog;
        $catalog->title = $request->get('title');
        $catalog->slug = $slug;
        $catalog->parent_id = $request->get('parent_id');
        $catalog->description = $request->get('description');
        $catalog->save();


//
//

//
//        $catalog = Catalog::create([
//            'title' => $request->category['title'],
//            'slug' => $slug,
//            'parent_id' => $request->category['parent_id'] ?? null,
//        ]);
//        return $catalog;
        $files = $request->get('files');
        foreach ($files as $key => $file) {

            $path = 'images/' . uniqid() . '.jpg';
            Img::make($file)->resize(300, 200)->save($path);
            $image = new Image;
            $image->path = $path;
            $catalog->images()->save($image);


        }

        return $catalog;
//        $ext = $files[0]->getClientOriginalExtension();
//        $fileName = $files[0]->getClientOriginalName();

    }


    public function show($catalog)
    {
//        return $catalog;
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
