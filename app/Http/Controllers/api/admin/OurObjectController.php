<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\OurObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class OurObjectController extends Controller
{

    private $image;

    public function __construct(Image $image)
    {
        $this->image = $image;
    }


    public function index()
    {
        return OurObject::with('images', 'catalogs', 'ceilings')->orderBy('id', 'DESC')->get();
    }


    public function store(Request $request)
    {
        $ourObject = OurObject::make([
            'title' => $request->get('title'),
            'address' => $request->get('address'),
            'square' => $request->get('square'),
            'description' => $request->get('description'),
            'price' => $request->get('price'),
        ]);
        $ourObject->save();
        $files = $request->get('images');
        return DB::transaction(function () use ($request, $files, $ourObject) {
            $image = new Image();
            $image->saveImage($files, $ourObject, true);
            $ourObject->catalogs()->attach($request->get('catalogs'));
            $ourObject->ceilings()->attach($request->get('ceilings'));
        });
    }


    public function show($id)
    {
        return OurObject::with('images', 'catalogs', 'ceilings')->where('id', $id)->first();
    }


    public function update(Request $request, $id)
    {
        $ourObject = OurObject::findOrFail($id);
        $ourObject->fill($request->except(['id']));
        $ourObject->save();
        $ourObject->catalogs()->sync($request->get('catalogs'));
        $ourObject->ceilings()->sync($request->get('ceilings'));
        return $ourObject;
    }


    public function destroy($id)
    {
        $obj = OurObject::where('id', $id)->first();
        try {
            foreach ($obj->images as $image) {
                if (Storage::disk('local')->exists('/public/' . $image->path)) {
                    Storage::disk('local')->delete('/public/' . $image->path);
                    Storage::disk('local')->delete('/public/' . $image->thumb);
                }
            }
            $obj->images()->delete();
            $obj->delete();
            return OurObject::all();
        } catch (Exception $e) {
            return $e;
        }
    }

    public function addImages(Request $request)
    {
        $entity = OurObject::class;
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

    public function changeLanding($id)
    {
        $object = OurObject::findOrFail($id);
        $object->landing = !$object->landing;
        $object->save();
        return $object;
    }
}
