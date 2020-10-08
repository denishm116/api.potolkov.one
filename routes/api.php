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
    Route::apiResource('/catalog', 'api\admin\CatalogController');
    Route::post('/catalog/{catalog}/up', 'api\admin\CatalogController@up');
    Route::post('/catalog/{catalog}/down', 'api\admin\CatalogController@down');
    Route::post('/catalog/{catalog}/destroy', 'api\admin\CatalogController@destroy');

    Route::apiResource('/lightning_catalog', 'api\admin\LightningCatalogController');
    Route::post('/lightning_catalog/{lightning_catalog}/up', 'api\admin\LightningCatalogController@up');
    Route::post('/lightning_catalog/{lightning_catalog}/down', 'api\admin\LightningCatalogController@down');
    Route::post('/lightning_catalog/{lightning_catalog}/destroy', 'api\admin\LightningCatalogController@destroy');

    Route::apiResource('/component_catalog', 'api\admin\ComponentCatalogController');
    Route::post('/component_catalog/{component_catalog}/up', 'api\admin\ComponentCatalogController@up');
    Route::post('/component_catalog/{component_catalog}/down', 'api\admin\ComponentCatalogController@down');
    Route::post('/component_catalog/{component_catalog}/destroy', 'api\admin\ComponentCatalogController@destroy');

    Route::apiResource('/ceilings', 'api\admin\CeilingController');

});

Route::group(['prefix' => '/frontend'], function () {
    Route::get('/getCeilingCatalog', 'api\frontend\FrontendController@ceiling_catalog');
    Route::get('/getLightningCatalog', 'api\frontend\FrontendController@lightning_catalog');
    Route::get('/getComponentCatalog', 'api\frontend\FrontendController@component_catalog');
    Route::get('/getChildren/{slug}', 'api\frontend\FrontendController@children');

    Route::get('/getCeilings/{slug}', 'api\frontend\FrontendController@ceilings');
});
