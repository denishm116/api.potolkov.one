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

    public function index()
    {
        return Ceiling::all();
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
        foreach ($files as $key => $file) {

            $path = 'images/' . uniqid() . '.jpg';
            $main = $file['main'];
            $resize = Img::make($file['image'])->resize(300, 200)->encode('jpg', 100);
            Storage::disk('public')->put($path, $resize);
//
            $image = new Image;
            $image->path = $path;
            $image->main = $main;
            $ceiling->images()->save($image);
        }
        return $ceiling;
    }


    public function show($slug)
    {
        return Ceiling::with('images')->where('slug', $slug)->get();
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
    public function destroy($id)
    {
        //
    }
}
