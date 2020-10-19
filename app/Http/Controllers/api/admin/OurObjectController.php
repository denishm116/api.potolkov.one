<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\models\OurObject;
use Illuminate\Http\Request;


class OurObjectController extends Controller
{

    private $image;

    public function __construct(Image $image)
    {
        $this->image = $image;
    }


    public function index()
    {
        return OurObject::all();
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

        $this->image->saveImage($files, $ourObject, true);
        $ourObject->catalogs()->attach($request->get('catalogs'));
        $ourObject->ceilings()->attach($request->get('ceilings'));

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
        dd($id);
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
}
