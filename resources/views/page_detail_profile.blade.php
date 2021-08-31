@extends('layouts.layout_app')

@section('header')
<style>
</style>
@endsection

@section('title')
| {{ $title }}
@endsection

@section('content')
<div class="p-3 content-container">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">
            {{ $title }}
        </h1>
    </div>
    <div class="d-flex flex-row flex-wrap">
        <div class="col-md-12">
            <article class="blog-post">
                <div class="d-flex py-2">
                    <div style="width:100px;height:100px;" class="bg-dark">
                        <img src="{{ asset($user->image) }}" alt="" class="w-100 h-100" style="object-fit: cover;">
                    </div>
                    <div class="d-flex flex-column justify-content-end ps-3">
                        <h2 class="blog-post-title">{{ $user->username }}</h2>
                        <p class="blog-post-meta m-0">Role: {{ $user->role }}</p>
                    </div>
                </div>
            </article>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script>
</script>
@endsection