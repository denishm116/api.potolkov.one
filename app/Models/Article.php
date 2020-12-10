<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Article extends Model
{
    protected $fillable = ['title', 'meta_description', 'description'];
    protected $hidden = ['updated_at', 'deleted_at'];
    protected $appends = ['mainImage'];

    public function images()
    {
        return $this->morphMany('App\Models\Image', 'imageable');
    }

    public function subArticles()
    {
        return $this->hasMany('App\Models\SubArticle', 'article_id');
    }

    public function catalogs()
    {
        return $this->morphedByMany('App\Models\Catalog', 'articable');
    }

    public function ceilings()
    {
        return $this->morphedByMany('App\Models\Ceiling', 'articable');
    }

    public function getMainImageAttribute()
    {
        return $this->images->where('main', 1)->first()->path ?? null;
    }

    static function create($request)
    {
        $image = new Image();
        $article = self::make([
            'title' => $request->get('title'),
            'meta_description' => $request->get('metaDescription'),
            'description' => $request->get('description'),
        ]);
        $article->save();

        SubArticle::create($request->get('subArticles'), $article);

        $files = $request->get('images');
        $image->saveImage($files, $article, true);

        $article->catalogs()->attach($request->get('catalogs'));
        $article->ceilings()->attach($request->get('ceilings'));
    }

    static function updateArticle($request, $id)
    {
        $article = self::findOrFail($id);
        $article->fill($request->except(['id']));
        $article->save();
        $article->catalogs()->sync($request->get('catalogs'));
        $article->ceilings()->sync($request->get('ceilings'));

        $subArticle = SubArticle::where('article_id', $id)->firstOrFail();

        $subArticle->updateSubArticle($request->get('subArticles'), $article);

        return $article;
    }
}
