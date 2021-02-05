<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// user
Route::post('/user/login','UserController@show');

// bahan baku
Route::get('/bahan-baku','BahanBakuController@all');
Route::post('/bahan-baku','BahanBakuController@store');
Route::get('/bahan-baku/{id}','BahanBakuController@detail');
Route::put('/bahan-baku/{id}','BahanBakuController@update');
Route::delete('/bahan-baku/{id}','BahanBakuController@delete');

// stok masuk
Route::get('/stok-masuk','StokMasukController@all');
Route::post('/stok-masuk','StokMasukController@store');
Route::get('/stok-masuk/{id}','StokMasukController@detail');
Route::delete('/stok-masuk/{id}','StokMasukController@delete');

// stok keluar
Route::get('/stok-keluar','StokKeluarController@all');
Route::post('/stok-keluar','StokKeluarController@store');
Route::get('/stok-keluar/{id}','StokKeluarController@detail');
Route::delete('/stok-keluar/{id}','StokKeluarController@delete');

// produksi
Route::get('/produksi','ProduksiController@all');
Route::get('/produksi/{id}','ProduksiController@detail');
Route::put('/produksi/{id}','ProduksiController@update');

// supplier
Route::get('/supplier','SupplierController@all');
Route::post('/supplier','SupplierController@store');
Route::get('/supplier/{id}','SupplierController@detail');
Route::put('/supplier/{id}','SupplierController@update');
Route::put('/supplier-delete/{id}','SupplierController@delete');

// penawaran
Route::get('/penawaran','PenawaranController@all');
Route::post('/penawaran','PenawaranController@store');
Route::get('/penawaran/{id}','PenawaranController@detail');
Route::put('/penawaran/{id}','PenawaranController@update');
Route::delete('/penawaran/{id}','PenawaranController@delete');

// kriteria
Route::get('/kriteria','KriteriaController@all');
Route::post('/kriteria','KriteriaController@store');
Route::get('/kriteria/{id}','KriteriaController@detail');
Route::put('/kriteria/{id}','KriteriaController@update');
Route::delete('/kriteria/{id}','KriteriaController@delete');

// topsis
Route::get('/topsis','TopsisController@all');
Route::post('/topsis','TopsisController@store');

// saw
Route::get('/saw','SAWController@all');
Route::post('/saw/{id}','SAWController@store');