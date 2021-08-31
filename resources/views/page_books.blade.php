@extends('layouts.layout_app')

@section('header')
<style>
    .book-container,
    .book-container:hover {
        text-decoration: none;
        color: unset;
    }
</style>
@endsection

@section('title')
| {{ $title }}
@endsection

@section('content')
<div class="p-3 content-container">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">{{ $title }}</h1>
    </div>
    <div class="d-flex flex-row flex-wrap">
        @foreach ($books as $book)
        <a href="{{ url('/page/books/' . $book->book_id) }}" class="book-container">
            <div style="width: 230px;" class="p-2 card m-1 bg-white">
                <div class="bg-dark" style="width: 100%;height: 200px;">
                    <img src="{{ asset($book->image) }}" alt="" style="width: 100%;height: 100%;object-fit: cover;">
                </div>
                <div class="d-flex flex-column pt-1">
                    <span class="text-sm-center">{{ $book->title }}</span>
                    <span class="text-sm-center" style="font-size: 11px;">{{ $book->author_name }}</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endsection

@section('footer')
<script>
</script>
@endsection