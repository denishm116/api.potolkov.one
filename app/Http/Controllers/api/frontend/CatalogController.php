<?php

namespace App\Http\Controllers\api\frontend;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function catalog()
    {
        return Catalog::defaultOrder()->withDepth()->get()->toTree();
    }


}
