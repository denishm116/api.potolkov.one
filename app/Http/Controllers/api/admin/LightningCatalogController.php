<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;

use App\Models\Catalog;
use App\Models\Image;
use App\Models\LightningCatalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as Img;

class LightningCatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return LightningCatalog::defaultOrder()->withDepth()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

        foreach ($files as $key => $file) {

            $path = 'images/' . uniqid() . '.jpg';
            $main = $file['main'];
            $resize = Img::make($file['image'])->resize(300, 200)->encode('jpg', 100);
            Storage::disk('public')->put($path, $resize);
//
            $image = new Image;
            $image->path = $path;
            $image->main = $main;
            $catalog->images()->save($image);
        }
        return $catalog;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($lightning_catalog)
    {
        return LightningCatalog::with('images')->where('slug', $lightning_catalog)->get();
    }


    public function up($lightning_catalog)
    {
        return LightningCatalog::where('slug', $lightning_catalog)->first()->up();
    }


    public function down($catalog)
    {
        return LightningCatalog::where('slug', $catalog)->first()->down();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
}
