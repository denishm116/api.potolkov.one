<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Image;
use App\Models\SubArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{


    public function index()
    {
        return Article::with('catalogs', 'ceilings', 'subArticles')->get();
    }


    public function store(Request $request)
    {
        Article::create($request);
    }


    public function show($id)
    {
        return Article::with('images', 'catalogs', 'ceilings', 'subArticles', 'subArticles.images')->where('id', $id)->first();
    }


    public function update(Request $request, $id)
    {
       return Article::updateArticle($request, $id);
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


}
