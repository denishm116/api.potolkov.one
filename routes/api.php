<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => '/auth', ['middleware' => ['throttle:20,5']]], function () {
    Route::post('/register', 'api\auth\RegisterController@register');
    Route::post('/login', 'api\auth\LoginController@login');
    Route::post('/logout', 'api\auth\LoginController@logout');
    Route::get('/login/{service}', 'api\auth\SocialLoginController@redirect');
    Route::get('/login/{service}/callback', 'api\auth\SocialLoginController@callback');

});

Route::group(['middleware' => ['jwt.auth']], function () {
    Route::get('/home', 'HomeController@testapi');

});
Route::get('/test', 'HomeController@testapi2');

Route::group(['prefix' => '/admin'], function () {


    Route::apiResource('/Article', 'api\admin\ArticleController');
    Route::get('/Article/changeMainImage/{id}', 'api\admin\ArticleController@changeMainImage');
    Route::get('/Article/deleteImage/{id}', 'api\admin\ArticleController@deleteImage');
    Route::post('/Article/addImages', 'api\admin\ArticleController@addImages');

    Route::apiResource('/ourObject', 'api\admin\OurObjectController');
    Route::get('/ourObject/changeMainImage/{id}', 'api\admin\OurObjectController@changeMainImage');
    Route::get('/ourObject/deleteImage/{id}', 'api\admin\OurObjectController@deleteImage');
    Route::post('/ourObject/addImages', 'api\admin\OurObjectController@addImages');

    Route::apiResource('/catalog', 'api\admin\CatalogController');
    Route::post('/catalog/{catalog}/up', 'api\admin\CatalogController@up');
    Route::post('/catalog/{catalog}/down', 'api\admin\CatalogController@down');
    Route::post('/catalog/{catalog}/destroy', 'api\admin\CatalogController@destroy');
    Route::get('/catalog/changeMainImage/{id}', 'api\admin\CatalogController@changeMainImage');
    Route::get('/catalog/deleteImage/{id}', 'api\admin\CatalogController@deleteImage');
    Route::post('/catalog/addImages', 'api\admin\CatalogController@addImages');


    Route::apiResource('/lightning_catalog', 'api\admin\LightningCatalogController');
    Route::post('/lightning_catalog/{lightning_catalog}/up', 'api\admin\LightningCatalogController@up');
    Route::post('/lightning_catalog/{lightning_catalog}/down', 'api\admin\LightningCatalogController@down');
    Route::post('/lightning_catalog/{lightning_catalog}/destroy', 'api\admin\LightningCatalogController@destroy');
    Route::get('/lightning_catalog/changeMainImage/{id}', 'api\admin\LightningCatalogController@changeMainImage');
    Route::get('/lightning_catalog/deleteImage/{id}', 'api\admin\LightningCatalogController@deleteImage');
    Route::post('/lightning_catalog/addImages', 'api\admin\LightningCatalogController@addImages');


    Route::apiResource('/component_catalog', 'api\admin\ComponentCatalogController');
    Route::post('/component_catalog/{component_catalog}/up', 'api\admin\ComponentCatalogController@up');
    Route::post('/component_catalog/{component_catalog}/down', 'api\admin\ComponentCatalogController@down');
    Route::post('/component_catalog/{component_catalog}/destroy', 'api\admin\ComponentCatalogController@destroy');
    Route::get('/component_catalog/changeMainImage/{id}', 'api\admin\ComponentCatalogController@changeMainImage');
    Route::get('/component_catalog/deleteImage/{id}', 'api\admin\ComponentCatalogController@deleteImage');
    Route::post('/component_catalog/addImages', 'api\admin\ComponentCatalogController@addImages');


    Route::apiResource('/ceilings', 'api\admin\CeilingController');
    Route::get('/ceilings/changeMainImage/{id}', 'api\admin\CeilingController@changeMainImage');
    Route::get('/ceilings/deleteImage/{id}', 'api\admin\CeilingController@deleteImage');
    Route::post('/ceilings/addImages', 'api\admin\CeilingController@addImages');

    Route::apiResource('/lightnings', 'api\admin\LightningController');
    Route::get('/lightnings/changeMainImage/{id}', 'api\admin\LightningController@changeMainImage');
    Route::get('/lightnings/deleteImage/{id}', 'api\admin\LightningController@deleteImage');
    Route::post('/lightnings/addImages', 'api\admin\LightningController@addImages');

    Route::apiResource('/components', 'api\admin\ComponentController');
    Route::get('/components/changeMainImage/{id}', 'api\admin\ComponentController@changeMainImage');
    Route::get('/components/deleteImage/{id}', 'api\admin\ComponentController@deleteImage');
    Route::post('/components/addImages', 'api\admin\ComponentController@addImages');



});

Route::group(['prefix' => '/frontend'], function () {
    Route::get('/getCeilingCatalog', 'api\frontend\FrontendController@ceiling_catalog');
    Route::get('/getChildren/{slug}', 'api\frontend\FrontendController@children');
    Route::get('/getCeilings/{slug}', 'api\frontend\FrontendController@ceilings');


    Route::get('/getLightningCatalog', 'api\frontend\FrontendController@lightning_catalog');
    Route::get('/getLightningChildren/{slug}', 'api\frontend\FrontendController@lightning_children');
    Route::get('/getLightning/{slug}', 'api\frontend\FrontendController@lightnings');

    Route::get('/getComponentCatalog', 'api\frontend\FrontendController@component_catalog');
    Route::get('/getComponentChildren/{slug}', 'api\frontend\FrontendController@component_children');
    Route::get('/getComponent/{slug}', 'api\frontend\FrontendController@components');




});
