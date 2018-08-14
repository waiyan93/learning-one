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
//Ajax Routes
Route::post('ebooks/ajax/selectedEbook', 'EbookController@selectedEbook');
//Resource Routes
Route::get('/', 'EbookController@index');
Route::get('ebooks/create', 'EbookController@create')->name('ebooks.create');
Route::post('ebooks', 'EbookController@store')->name('ebooks.store');
Route::get('ebooks/{id}/page/{number}/edit', 'EbookController@edit')->name('ebooks.edit');
Route::get('ebooks/{id}', 'EbookController@show')->name('ebooks.show');
Route::post('contents', 'ContentController@store')->name('contents.store');
Route::post('contents/add-content', 'ContentController@addContent')->name('contents.add');

Route::get('clear', function(){
    session()->flush();
});