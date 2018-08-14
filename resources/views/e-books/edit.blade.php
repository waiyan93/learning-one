@extends('layouts.master')
@section('title', '| PDF Edit')
@section('css')
    <link rel="stylesheet" href="{{ asset('jquery-ui/jquery-ui.min.css') }}">
@endsection
@section('btn')
    <button id="select-content" class="btn btn-primary">Select Tool</button>
    <form action="{{ route('contents.store') }}" method="POST" class="ml-auto">
    @csrf
        <button id="export-pdf" class="btn btn-success">Export PDF</button>
    </form>
@endsection
@section('content')
<div class="row content-area">
    <div class="col-lg-12 col-md-12 mt-2">
        @if(session()->has('success'))
            <div class="alert alert-success" id="success-alert">
                <button type="button" class="close" data-dismiss="alert">x</button>
                {{ session()->get('success') }}
            </div>
        @endif
        <div class="select-box">
            <button id="add-link" type="button" class="float-right" data-toggle="modal" data-target="#addLinkModal">Add Link</button>
                <h5 class="ui-widget-header pb-2">Drag Me</h5>
            <button id="cancel" class="float-left">Deselect</button>
        </div>
        <div id='viewer'>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addLinkModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Add Link</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('contents.add') }}" method="POST">
                    @csrf
                    <div class="modal-body mx-3">
                        <div class="md-form mb-2">
                            <label for="link-type">Link Type :</label>
                            <select name="link_type" id="link-type" class="form-control">
                                <option value="0">Choose Link Type</option>
                                @foreach ($linkTypes as $linkType)
                                <option value="{{ $linkType->id }}">{{ $linkType->type }}</option>
                                @endforeach
                            </select>
                        </div>
                            <div class="md-form mb-2">
                            <label for="link">Link :</label>
                            <input type="text" name="link" id="link" class="form-control" required="required">
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <input type="hidden" class="page-number" name="page_number" value="{{ $pageNumber }}">
                        <input class="x-position" type="hidden" name="x_position" value="">
                        <input class="y-position" type="hidden" name="y_position" value="">
                        <input class="box-width" type="hidden" name="width" value="250">
                        <input class="box-height" type="hidden" name="height" value="150">
                        <button id="btn-save" class="btn btn-success btn-block">Save</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('jquery-ui/jquery-ui.min.js') }}"></script>
<script>
    var url = '{{ asset("data/$ebook->source") }}';
    var thePdf = null;
    var scale = 1;
    PDFJS.getDocument(url).promise.then(function(pdf) {
        thePdf = pdf;
        var page = {{ $pageNumber }};
        viewer = document.getElementById('viewer');
        canvas = document.createElement("canvas");    
        canvas.className = 'page-canvas'; 
        canvas.id = 'page-'+page ;
        viewer.appendChild(canvas);  
        renderPage(page, canvas);
    });

    function renderPage(pageNumber, canvas) {
        thePdf.getPage(pageNumber).then(function(page) {
            viewport = page.getViewport(scale);
            canvas.height = viewport.height;
            canvas.width = viewport.width;    
            $('.page-width').val(canvas.width);
            $('.page-height').val(canvas.height);      
            page.render({canvasContext: canvas.getContext('2d'), viewport: viewport});
        });
    }

    $(document).ready(function(){
        var xPosition = 0;
        var yPosition = 0;
        var boxWidth = 250;
        var boxHeight = 150;
        
        $('#select-content').click(function(){
            $('.select-box').show(); 
            $('.select-box').css('position', 'absolute'); 
            $('.select-box').draggable(); 
            $('.select-box').resizable(); 
        });

        $('.select-box').draggable({
            start: function( event, ui ) {
                $('.select-box h5').remove();
                $('#add-link').hide();
                $('#cancel').hide();
            },
            stop: function( event, ui ) {
                xPosition = ui.position.left;
                yPosition = ui.position.top;
                $('.x-position').html('X-Position(px) : '+ xPosition);
                $('.y-position').html('Y-Position(px) : '+ yPosition);
                $('.x-position').val(xPosition);
                $('.y-position').val(yPosition);
                $('#add-link').show();
                $('#cancel').show();
            },
        });

        $('.select-box').resizable({
            stop: function( event, ui ) {
                boxWidth = ui.size.width;
                boxHeight = ui.size.height;
                $('.box-width').html('Width(px) : '+ boxWidth);
                $('.box-height').html('Height(px) : '+ boxHeight);
                $('.box-width').val(boxWidth);
                $('.box-height').val( boxHeight);
            }
        });

        $("#add-link").click(function(){
            modal({show: true,});
        });

        $('#cancel').click(function() {
            $(".select-box").hide();
        });
    
        $("#success-alert").fadeTo(3000, 100).slideUp(500, function(){
            $("#success-alert").slideUp(500);
        });   

        $('#export-pdf').click(function(){

        });
    });
</script>
@endsection