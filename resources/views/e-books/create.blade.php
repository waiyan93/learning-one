@extends('layouts.master')
@section('title', '| Add PDF')
@section('css')
@endsection
@section('content')
    <div class="row content-area">
        <div class="col-lg-12 col-md-12">
            <div class="row mt-5">
                <div class="col-lg-8 col-md-8 offset-lg-2 offset-md-2">
                    <div class="jumbotron">
                        <h1 class="display-4">Upload Ebook</h1>
                        <hr class="my-4">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger" id="error-alert">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif
                        <form action="{{ route('ebooks.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                            <input type="file" name="pdf" class="form-control">
                            <br>
                            <br>
                            <button class="btn btn-primary btn-block" href="#" role="button">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
<script>
    $(document).ready(function(){
        $("#error-alert").fadeTo(3000, 100).slideUp(500, function(){
            $("#error-alert").slideUp(500);
        });   
    });
</script>
@endsection