
@extends('frontend.layouts.app')

@section('title', $page->page_title)

@section('content')
    <div class="container">
        <p>{!! $page->content !!}</p>
    </div>
@endsection
