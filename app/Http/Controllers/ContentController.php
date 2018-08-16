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
        $ebook = Ebook::findOrFail(session()->get('ebookId'));
        $contents = session()->get('contents');
        return view('contents.index', ['ebook' => $ebook, 'contents' => $contents]);
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
    * @param  \Illuminate\Http\content  $content
    * @return \Illuminate\Http\Response
    */
    public function store()
    {
        $ebook = Ebook::findOrFail(session()->get('ebookId'));
        $pdf = new Fpdi\TcpdfFpdi(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // get the page count
        $pageCount = $pdf->setSourceFile('storage/'.$ebook->original);
        $contents = session()->get('contents');
        /**
        * FPDI conversion
        */
        // initiate FPDI
        // FPDF
        // $pdf = new Fpdi\Fpdi('P', 'pt', 'Letter');
        // TCPDF
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $pdf->AddPage();
            $pdf->useTemplate($templateId, [
                'adjustPageSize' => true, 'width' => 600, 'height' => 750
            ]); 
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            foreach($contents as $content)
            {
                /**
                * Selected x and y position and convert to pt
                */
                $xPosition = intval(($content['xPosition'] - 25 ) * 0.75);
                $yPosition = intval(($content['yPosition'] - 5 ) * 0.75);
        
                /**
                * Reduce x-position if x > 300 and convert to pt
                */
                if($content['xPosition'] > 300)
                {
                    $xPosition = intval(($content['xPosition'] - 24 ) * 0.75);
                }

                /**
                * Reduce y-position if x > 600 and convert to pt
                */
                if($content['yPosition'] > 600)
                {
                    $yPosition = intval(($content['yPosition'] - 6 ) * 0.75);
                }

                /**
                * selected box width and height in pt
                */
                $boxWidth = intval($content['width'] * 0.75);
                $boxHeight = intval($content['height'] * 0.75);

                /**
                * link and link-type to insert in selected area
                */
                $linkType = $content['linkType'];
                $link = $content['link'];
                if($pageNo == $content['pageNumber']) {
                    if ($linkType == 1) {
                        $pdf->SetAlpha(0.4);
                        $pdf->SetFillColor(85,217,192);
                        $pdf->SetDrawColor(2,35,28);
                        $pdf->Rect($xPosition, $yPosition, $boxWidth, $boxHeight, 'DF');
                        $pdf->Link( $xPosition, $yPosition, $boxWidth, $boxHeight, $link, $spaces = 0 );
                    }elseif($linkType == 2) {
                        $fbLink = 'https://www.facebook.com/sharer/sharer.php?u='.$link;
                        $twitterLink = 'https://www.facebook.com/sharer/sharer.php?u='.$link;
                        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                        $pdf->SetDrawColor(0,0,0);
                        $pdf->SetAlpha(0.5);
                        $pdf->SetFillColor(85,217,192);
                        $pdf->Rect($xPosition, $yPosition, $boxWidth, $boxHeight, 'DF');
                        $pdf->SetAlpha(1);
                        $iconXPosition = ($xPosition+$boxWidth) - 30;
                        $pdf->Image('images/facebook.png', $iconXPosition-30, $yPosition,  30, 30, 'PNG', $fbLink);
                        $pdf->Image('images/twitter.png',  $iconXPosition, $yPosition, 30, 30, 'PNG', $twitterLink);
                    }elseif($linkType == 3) {
                        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
                        $pdf->SetDrawColor(2,35,28);
                        $pdf->Rect($xPosition, $yPosition, $boxWidth, $boxHeight, 'D');
                        $pdf->SetAlpha(0.8);
                        $imageXPosition = (($boxWidth/2) + $xPosition) - ($boxWidth/2)/2;
                        $imageYPosition = (($boxHeight/2) + $yPosition) - ($boxHeight/2)/2;
                        $pdf->Image('images/play.png',  $imageXPosition, $imageYPosition, $boxWidth/2, $boxHeight/2, 'PNG', $link);
                    }
                }
            }
        }     
        $exp = explode('/', $ebook->original);
        $updatedPath = 'updated-ebooks/'.$exp[1];
        $pdf->Output($_SERVER['DOCUMENT_ROOT'].'/storage/'.$updatedPath, 'F');
        $ebook->updated = $updatedPath;
        $ebook->save();
        session()->forget('ebookId');
        session()->forget('contents');
        return redirect('/')->with('success', 'A PDF is modified!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function show()
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
     * @param  \Illuminate\Http\content  $content
     * @param  \App\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
    }

    public function addContent(Request $request) {
        $content = [
            'linkType' => $request->link_type,
            'link' => $request->link,
            'pageNumber' => $request->page_number,
            'xPosition' => $request->x_position,
            'yPosition' => $request->y_position,
            'width' => $request->width,
            'height' => $request->height
        ];
        if(session()->has('contents'))
        {   
            session()->push('contents', $content);
        } else {
            session()->put('contents', []);
            session()->push('contents', $content);
        }
        return redirect()->back()->with('success', 'A content is added into the page!');
    }
    
    public function clearAll()
    {
        // $ebook = Ebook::findOrFail(session()->get('ebookId'));
        session()->flush();
        return redirect('/')->with('success', 'All added contents are successfully clear!');
    }
}
