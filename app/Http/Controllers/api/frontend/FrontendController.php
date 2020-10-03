<?php

namespace App\Http\Controllers\api\frontend;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\Ceiling;
use App\Models\LightningCatalog;
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

    public function children($slug)
    {
        return Catalog::with('children')->where('slug', $slug)->get();
    }


    public function ceiling($slug)
    {
        return Ceiling::with('images')->where('slug', $slug)->get();
    }


}
