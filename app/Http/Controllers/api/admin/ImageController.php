<?php

namespace App\Http\Controllers\api\admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Catalog;
use App\Models\Ceiling;
use App\Models\Component;
use App\Models\ComponentCatalog;
use App\Models\Image;
use App\Models\Lightning;
use App\Models\LightningCatalog;
use App\Models\OurObject;
use App\Models\SubArticle;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    private $image;

    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    public function addImages(Request $request)
    {
        switch ($request->get('entityName')) {
            case 'Article':
                $entity = Article::class;
                break;
            case 'SubArticle':
                $entity = SubArticle::class;
                break;
            case 'Catalog':
                $entity = Catalog::class;
                break;
            case 'Ceiling':
                $entity = Ceiling::class;
                break;
            case 'LightningCatalog':
                $entity = LightningCatalog::class;
                break;
            case 'Lightning':
                $entity = Lightning::class;
                break;
            case 'Component':
                $entity = Component::class;
                break;
            case 'ComponentCatalog':
                $entity = ComponentCatalog::class;
                break;
            default:
                $entity = OurObject::class;
        }
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
