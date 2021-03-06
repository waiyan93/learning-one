@extends('layouts.master')
@section('title', '| All Contents')
@section('btn')
 
@endsection
@section('content')
    <div class="row content-area">
        <div class="col-lg-12 col-md-12 bg-secondary">
           <div class="row">
                <div class="col-lg-12 col-md-12">
                        @if(session()->has('success'))
                        <div class="alert alert-success mt-2" id="success-alert">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            {{ session()->get('success') }}
                        </div>
                        @endif
                    <div class="row my-2">
                        <div class="col-lg-4 col-md-4">
                            <a href="{{ route('ebooks.show', $ebook->id) }}" class="text-warning navbar-brand">{{ $ebook->title }}<a>
                        </div>
                        <div class="col-lg-4 col-md-4 text-center">
                            @if(session()->has('contents'))
                            <a class="text-white navbar-brand">Total Content(s): {{ count(session()->get('contents')) }}</a>
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div class="row">
                                <div class="col-lg-9 col-md-9">
                                <form action="{{ route('contents.clear.all') }}" method="POST">
                                    <input type="hidden" name="_method" value="delete" />
                                    @csrf
                                    <input type="submit" name="clear_all" id="clear-all" class="btn btn-danger mr-2 float-right" value="Clear All Contents">
                                </form>
                                </div>
                                <div class="col-lg-3 col-md-3">
                                <form action="{{ route('contents.store') }}" method="POST">
                                    @csrf
                                    <button id="export-pdf" class="btn btn-warning mx-2 float-right">Export PDF</button>
                                </form>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <hr class="mt-0 mb-2">
                    <div class="row" id="thumbnail-viewer">
                    </div>
                </div>
           </div>
        </div>
    </div>
@endsection
@section('js')
<script>  
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var pageNumbers = [];
        @if(count($contents) > 0)
        @foreach($contents as $content)
            var pageNumber = '{{ $content["pageNumber"] }}';
            if(pageNumbers.includes(pageNumber)) {
                pageNumbers = pageNumbers;
            }else{
                pageNumbers.push(pageNumber);
            }
        @endforeach
        var url = '{{ asset("storage/$ebook->edited") }}';
        var thePdf = null;
        var scale = 1;
        PDFJS.getDocument(url).promise.then(function(pdf) {
            thePdf = pdf;
            var viewer = document.getElementById('thumbnail-viewer');
            for(i = 0; i < pageNumbers.length; i++) {
                var page = parseInt(pageNumbers[i]);
                var col = document.createElement('div');
                col.className = 'col-lg-2 col-md-2 mb-2';
                var card = document.createElement('div');
                card.className = 'card';
                var card_img_top = document.createElement('div');
                card_img_top.className = 'card-img-top';
                var card_body = document.createElement('div');
                card_body.className = 'card-body py-2';
                var card_title = document.createElement('h6');
                card_title.className = 'card-title';
                card_title.innerText = 'Page - '+ page;
                var action = document.createElement('button');
                action.className = 'btn btn-outline-primary btn-block btn-edit';
                action.id = page;
                action.innerText = 'Edit';
                var canvas = document.createElement("canvas");    
                canvas.className = 'thumbnail-cover'; 
                canvas.id = 'page-'+page;
                viewer.appendChild(col);
                col.appendChild(card);
                card.appendChild(card_img_top);
                card_img_top.appendChild(canvas);
                card.appendChild(card_body);
                card_body.appendChild(card_title);
                card_body.appendChild(action);
                renderPage(page, canvas);
            }
            $('.btn-edit').off('click').on('click', function(e) {
                var page_number = this.id;
                window.location.href = '{{ url("ebooks/$ebook->id/page") }}/' + page_number +'/edit';
            });
        });

        function renderPage(pageNumber, canvas) {
            thePdf.getPage(pageNumber).then(function(page) {
                var vp = page.getViewport(scale);
                canvas.width = 150;
                canvas.height = 200;
                var new_scale = Math.min(canvas.width / vp.width, canvas.height / vp.height);    
                viewport = page.getViewport(new_scale);
                page.render({
                    canvasContext: canvas.getContext('2d'), 
                    viewport: viewport
                });
            });
        }
        @else
            var viewer = document.getElementById('thumbnail-viewer');
            var jumbotron = document.createElement('div');
            jumbotron.className = 'jumbotron col-lg-8 col-md-8 offset-lg-2 offset-md-2 text-center';
            var msg = document.createElement('h1');
            msg.innerText = 'Whoops! There is no contents.';
            viewer.appendChild(jumbotron);
            jumbotron.appendChild(msg);
            $('#clear-all').hide();
        @endif
    });
</script>
@endsection