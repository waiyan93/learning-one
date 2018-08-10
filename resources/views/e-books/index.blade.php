@extends('layouts.master')
@section('title', '| PDF List')
@section('content')
    <div class="row content-area">
        <div class="col-lg-12 col-md-12 bg-secondary">
           <div class="row mt-4">
                <div class="col-lg-12 col-md-12">
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
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        var datas = [];
        @foreach($ebooks as $ebook)
            var data = {
                'id': '{{ $ebook->id }}',
                'title': '{{ $ebook->title }}',
                'path' : '{{ $ebook->path }}',
            }
            datas.push(data);
        @endforeach
        for (i = 0; i < datas.length; i++) { 
            (function () {
                var thePdf = null;
                var scale = 1;
                var url = datas[i].path;
                var title = datas[i].title;
                var ebook_id = datas[i].id;
                PDFJS.getDocument(url).promise.then(function(pdf) {
                    thePdf = pdf;
                    var viewer = document.getElementById('thumbnail-viewer');
                    var page = 1;
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
                    card_title.innerText = title;
                    var action = document.createElement('button');
                    action.className = 'btn btn-outline-primary btn-select btn-block';
                    action.innerText = 'SELECT';
                    action.id = ebook_id;
                    var canvas = document.createElement("canvas");    
                    canvas.className = 'thumbnail-cover'; 
                    canvas.id = 'cover-'+ebook_id;
                    viewer.appendChild(col);
                    col.appendChild(card);
                    card.appendChild(card_img_top);
                    card_img_top.appendChild(canvas);
                    card.appendChild(card_body);
                    card_body.appendChild(card_title);
                    card_body.appendChild(action);
                    renderPage(page, canvas);
                    $('.btn-select').off('click').on('click', function(e) {
                        var ebook_id = this.id;
                        $.ajax({
                            url: "{{ url('ebooks/ajax/selectedEbook') }}",
                            method: 'get',
                            data: {
                                'id': ebook_id
                            },
                            success: function(result) {
                                window.location.href = '{{ url("ebooks") }}/' + result.id; 
                            },
                        });
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
            }) ();
        }
    });
</script>
@endsection