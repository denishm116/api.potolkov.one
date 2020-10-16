<?php

namespace App\Http\Controllers\api\frontend;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\Ceiling;
use App\Models\Lightning;
use App\Models\Component;
use App\Models\LightningCatalog;
use App\Models\ComponentCatalog;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function ceiling_catalog()
    {
        return Catalog::with(['images','children', 'ceiling', 'children.ceiling'])->defaultOrder()->withDepth()->get()->toTree();
    }

    public function lightning_catalog()
    {
        return LightningCatalog::with(['images','children', 'lightnings', 'children.lightnings'])->defaultOrder()->withDepth()->get()->toTree();
    }

    public function component_catalog()
    {
        return ComponentCatalog::with(['images','children', 'components', 'children.components'])->defaultOrder()->withDepth()->get()->toTree();
    }



    public function children($slug)
    {
        return Catalog::with(['images','children', 'ceiling', 'children.ceiling'])->where('slug', $slug)->first();
    }

    public function lightning_children($slug)
    {
        return LightningCatalog::with(['images','children', 'lightnings', 'children.lightnings'])->where('slug', $slug)->first();
    }

    public function component_children($slug)
    {
        return ComponentCatalog::with(['images','children', 'components', 'children.components'])->where('slug', $slug)->first();
    }




    public function ceilings($slug)
    {
        return Ceiling::with(['images', 'catalog'])->where('slug', $slug)->first();
    }

    public function lightnings($slug)
    {
        return Lightning::with(['images', 'lightning_catalog'])->where('slug', $slug)->first();
    }

    public function components($slug)
    {
        return Component::with(['images', 'component_catalog'])->where('slug', $slug)->first();
    }


}
