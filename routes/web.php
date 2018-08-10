<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'EbookController@index');
Route::get('ebooks/ajax/selectedEbook', 'EbookController@selectedEbook');
Route::get('ebooks/ajax/selectedPage', 'EbookController@selectedPage');
Route::get('ebooks/{id}/edit', 'EbookController@edit')->name('ebooks.edit');
Route::post('ebooks/{id}/update', 'EbookController@update')->name('ebooks.update');
Route::get('ebooks/{id}', 'EbookController@show')->name('ebooks.show');