<?php

namespace App\Http\Controllers;

use App\Ebook;
use App\LinkType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreEbookRequest;

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
        return view('e-books.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEbookRequest $request)
    {
        if($request->hasFile('pdf'))
        {
            $pdf = $request->pdf;
            $fileName = $pdf->getClientOriginalName();
            $title = pathinfo($fileName, PATHINFO_FILENAME);
            $originalPath = Storage::disk('public')->putFile('original-ebooks', $request->file('pdf'));
            $exp = explode('/', $originalPath);
            $editedPath = 'edited-ebooks/'.$exp[1];
            Storage::disk('public')->copy($originalPath, $editedPath);

            Ebook::create([
                'title' => $title,
                'original' => $originalPath,
                'edited' => $editedPath
            ]);
            return redirect('/')->with('success', 'A PDF is added successfully!');
        }
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
        $linkTypes = LinkType::all();
        return view('e-books.edit', ['ebook' => $ebook, 'pageNumber' => $pageNumber, 'linkTypes' => $linkTypes]);
    }

    public function selectedEbook(Request $request) 
    {
        session()->put('ebookId', $request->id);
        return response()->json(['id' => $request->id]);
    }
}