<?php

namespace App\Http\Controllers\api\frontend;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\Ceiling;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function catalog()
    {
        return Catalog::with('images')->defaultOrder()->withDepth()->get()->toTree();
    }

    public function children($slug)
    {
        return Catalog::with(['children', 'children.ceiling'])->where('slug', $slug)->get();
    }


    public function ceiling($slug)
    {
        return Ceiling::with('images')->where('slug', $slug)->get();
    }


}
