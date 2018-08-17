@extends('layouts.master')
@section('title', '| Download PDF')
@section('content')
    <div class="row content-area">
        <div class="col-lg-12 col-md-12">
            @if(session()->has('success'))
            <div class="alert alert-success mt-2" id="success-alert">
                <button type="button" class="close" data-dismiss="alert">x</button>
                {{ session()->get('success') }}
            </div>
            @endif
            <div class="row mt-4">
                <div class="col-lg-8 col-md-8 offset-lg-2 offset-md-2">
                    <div class="jumbotron">
                        <h1 class="display-4">Download Ebook</h1>
                        <hr class="my-4">
                            <h4>Name: {{ $ebook->title }}</h4>
                            <br>
                            <br>
                            <a class="btn btn-primary btn-block" href="{{ route('ebooks.download', $ebook->id) }}" role="button">Download</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
