<?php

namespace App\Http\Controllers\api\frontend;

use App\Http\Controllers\Controller;
use App\Mail\OrderFromSite;
use App\Models\Article;
use App\Models\Catalog;
use App\Models\Ceiling;
use App\Models\Lightning;
use App\Models\Component;
use App\Models\LightningCatalog;
use App\Models\ComponentCatalog;
use App\Models\OurObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class FrontendController extends Controller
{
    public function ceiling_catalog()
    {
        return Catalog::with(['images', 'children', 'ceilings', 'children.ceilings'])->defaultOrder()->withDepth()->get()->toTree();
    }

    public function lightning_catalog()
    {
        return LightningCatalog::with(['images', 'children', 'lightnings', 'children.lightnings'])->defaultOrder()->withDepth()->get()->toTree();
    }

    public function component_catalog()
    {
        return ComponentCatalog::with(['images', 'children', 'components', 'children.components'])->defaultOrder()->withDepth()->get()->toTree();
    }


    public function children($slug)
    {
        return Catalog::defaultOrder()->where('slug', $slug)->with(['images', 'children', 'ceilings', 'children.ceilings'])->firstOrFail();
    }

    public function lightning_children($slug)
    {
        return LightningCatalog::with(['images', 'children', 'lightnings', 'children.lightnings'])->where('slug', $slug)->firstOrFail();
    }

    public function component_children($slug)
    {
        return ComponentCatalog::with(['images', 'children', 'components', 'children.components'])->where('slug', $slug)->firstOrFail();
    }


    public function ceilings($slug)
    {
        return Ceiling::with(['images', 'catalog'])->where('slug', $slug)->firstOrFail();
    }

    public function lightnings($slug)
    {
        return Lightning::with(['images', 'lightning_catalog'])->where('slug', $slug)->firstOrFail();
    }

    public function components($slug)
    {
        return Component::with(['images', 'component_catalog'])->where('slug', $slug)->firstOrFail();
    }

    public function ourObjectsForCeiling($slug)
    {
        return OurObject::with('images', 'catalogs', 'ceilings')->whereHas('ceilings', function($q) use($slug) {
            $q->where('slug', $slug);
        })->get() ?: false;
    }

    public function ArticlesForCeiling($slug)
    {
        return Article::with('images', 'catalogs', 'ceilings')->whereHas('ceilings', function($q) use($slug) {
            $q->where('slug', $slug);
        })->limit(4)->get();
    }

    public function ourObjectsForCatalog($slug)
    {
        return OurObject::with('images', 'catalogs', 'ceilings')->whereHas('catalogs', function($q) use($slug) {
            $q->where('slug', $slug);
        })->get();
    }

    public function ourObjectsForLanding()
    {
        return OurObject::with('images', 'catalogs', 'ceilings')->where('landing', 1)->get();
    }

    public function ArticlesForCatalog($slug)
    {
        return Article::with('images', 'catalogs', 'ceilings')->whereHas('catalogs', function($q) use($slug) {
            $q->where('slug', $slug);
        })->limit(4)->get();
    }

    public function articles()
    {
        return Article::with('images', 'catalogs', 'ceilings', 'subArticles')->get();
    }


    public function articlesShortList()
    {
        return Article::with('images', 'catalogs', 'ceilings', 'subArticles')->limit(4)->get();
    }



    public function article($id)
    {
        return Article::with('images', 'catalogs', 'ceilings', 'subArticles', 'subArticles.images')->where('id', $id)->first();
    }

    public function ourObjects()
    {
        return OurObject::with('images', 'catalogs', 'ceilings')->orderBy('id', 'DESC')->get();
    }

    public function ourObject($id)
    {
        return OurObject::with('images', 'catalogs', 'ceilings')->where('id', $id)->first();
    }

    public function sendMail(Request $request) {
        Mail::to('gospodinpotolkov@yandex.ru')->send(new OrderFromSite($request->all()));
    }
}
