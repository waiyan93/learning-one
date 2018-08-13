<?php

namespace App\Http\Controllers;

use App\Ebook;
use Illuminate\Http\Request;

class EbookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ebooks = Ebook::whereNull('deleted_at')->get();
        return view('e-books.index', ['ebooks' => $ebooks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ebook  $ebook
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ebook = Ebook::findOrFail($id);
        return view('e-books.show', ['ebook' => $ebook]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Ebook  $ebook
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $pageNumber)
    {
        $ebook = Ebook::findOrFail($id);
        return view('e-books.edit', ['ebook' => $ebook, 'pageNumber' => $pageNumber]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Ebook  $ebook
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Ebook  $ebook
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ebook $ebook)
    {
        //
    }

    public function selectedEbook(Request $request) 
    {
        session(['ebookId' => $request->id]);
        return response()->json(['id' => $request->id]);
    }
}
