<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    private $image;

    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    public function index()
    {
        return Article::with('catalogs', 'ceilings')->get();
    }


    public function store(Request $request)
    {
        $article = Article::make([
            'title' => $request->get('title'),
            'meta_description' => $request->get('meta_description'),
            'description' => $request->get('description'),
        ]);
        $article->save();
        $files = $request->get('images');

        $this->image->saveImage($files, $article, true);
        $article->catalogs()->attach($request->get('catalogs'));
        $article->ceilings()->attach($request->get('ceilings'));
    }


    public function show($id)
    {
        return Article::with('images', 'catalogs', 'ceilings')->where('id', $id)->first();
    }


    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);
        $article->fill($request->except(['id']));
        $article->save();
        $article->catalogs()->sync($request->get('catalogs'));
        $article->ceilings()->sync($request->get('ceilings'));
        return $article;
    }


    public function destroy($id)
    {
        $obj = Article::where('id', $id)->first();
        try {
            foreach ($obj->images as $image) {
                if (Storage::disk('local')->exists('/public/' . $image->path)) {
                    Storage::disk('local')->delete('/public/' . $image->path);
                }
            }
            $obj->images()->delete();
            $obj->delete();
            return Article::all();
        } catch (Exception $e) {
            return $e;
        }
    }

    public function addImages(Request $request)
    {

        $entity = Article::class;
        $this->image->addImagesWithTitle($request, $entity, true);
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
