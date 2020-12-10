<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubArticle extends Model
{
    protected $fillable = ['description', 'article_id'];
    protected $hidden = ['updated_at', 'deleted_at'];
    public $timestamps = false;

    public function images()
    {
        return $this->morphMany('App\Models\Image', 'imageable');
    }

    public function article()
    {
        return $this->belongsTo('App\Models\Article');
    }

    static function create($subArticles, $article)
    {
        $img = new Image();
        foreach ($subArticles as $item) {
            $subArticle = self::make([
                'description' => $item['description']
            ]);
            $subArticle->save();
            $images = $item['images'];
            $img->saveImage($images, $subArticle, true);
            $article->subArticles()->save($subArticle);
        }
    }

    public function updateSubArticle($subArticles, $article = false)
    {
        $withoutId = [];
        foreach ($subArticles as $item) {
            if (isset($item['id'])) {
                $subArticle = self::findOrFail($item['id']);
                $subArticle->description = $item['description'];
                $subArticle->save();
            } else {
                array_push($withoutId, $item);
            }
        }
        if (count($withoutId)) {
            $this->create($withoutId, $article);
        }
    }
}
