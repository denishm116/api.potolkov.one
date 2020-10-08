<?php

namespace App\Http\Controllers\api\frontend;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\Ceiling;
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
        return LightningCatalog::with(['images','children', 'lightning', 'children.lightning'])->defaultOrder()->withDepth()->get()->toTree();
    }

    public function component_catalog()
    {
        return ComponentCatalog::with(['images','children', 'component', 'children.lightning'])->defaultOrder()->withDepth()->get()->toTree();
    }

    public function children($slug)
    {
        return Catalog::with(['images','children', 'ceiling', 'children.ceiling'])->where('slug', $slug)->first();
    }


    public function ceilings($slug)
    {
        return Ceiling::with(['images', 'catalog'])->where('slug', $slug)->first();
    }


}
