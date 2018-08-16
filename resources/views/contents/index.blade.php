@extends('layouts.master')
@section('title', '| All Contents')
@section('btn')
    
@endsection
@section('content')
    <div class="row content-area">
        <div class="col-lg-12 col-md-12 bg-secondary">
           <div class="row">
                <div class="col-lg-12 col-md-12">
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
                            <form action="{{ route('contents.clear.all') }}" method="POST">
                                <input type="hidden" name="_method" value="delete" />
                                @csrf
                                <input type="submit" name="clear_all" class="btn btn-primary float-right" value="Clear All Contents">
                            </form>
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
        @foreach($contents as $content)
            var pageNumber = '{{ $content["pageNumber"] }}';
            pageNumbers.push(pageNumber);
        @endforeach
        var url = '{{ asset("data/$ebook->source") }}';
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
    });
</script>
@endsection