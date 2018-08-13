<?php

namespace App\Http\Controllers;

use \FPDF;
use App\Ebook;
use App\Content;
use \setasign\Fpdi;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $ebook = Ebook::findOrFail($request->ebook);
        /**
        * Selected x and y position and convert to pt
        */
        $xPosition = intval(($request->x_position - 25 ) * 0.75);
        $yPosition = intval(($request->y_position - 5 ) * 0.75);
        
        /**
        * Reduce x-position if x > 300 and convert to pt
        */
        if($request->x_position > 300)
        {
            $xPosition = intval(($request->x_position - 24 ) * 0.75);
        }

        /**
        * Reduce y-position if x > 600 and convert to pt
        */
        if($request->y_position > 600)
        {
            $yPosition = intval(($request->y_position - 6 ) * 0.75);
        }

        /**
        * selected box width and height in pt
        */
        $boxWidth = intval($request->width * 0.75);
        $boxHeight = intval($request->height * 0.75);

        /**
        * link and link-type to insert in selected area
        */
        $linkType = $request->link_type;
        $link = $request->link;

        /**
        * FPDI conversion
        */
        // initiate FPDI
        $pdf = new Fpdi\Fpdi('P', 'pt', 'Letter');
        // get the page count

        $pageCount = $pdf->setSourceFile('data/'.$ebook->path);

        // iterate through all pages
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            // import a page
            $templateId = $pdf->importPage($pageNo);

            $pdf->AddPage();

            // use the imported page and adjust the page size
            $pdf->useTemplate($templateId, [
                'adjustPageSize' => true, 'width' => 600, 'height' => 750
            ]); 

            // check page number to add link area
            if ($pageNo == $request->page_number) {
                $pdf->SetDrawColor(0, 255, 148);
                $pdf->Rect($xPosition, $yPosition, $boxWidth, $boxHeight, 'D');
                $pdf->Link($xPosition, $yPosition, $boxWidth, $boxHeight, $link);
            }
        }
        $fileName = 'data/'.$ebook->path;
        $pdf->Output($fileName, 'F');
        session()->forget('ebookId');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function show(Content $content)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function edit(Content $content)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Content $content)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function destroy(Content $content)
    {
        //
    }
}
