@extends('frontend.layouts.app')

@section('title', 'FAQs')

@section('content')
    <div class="container">
    <h1>{{ $page->page_title }}</h1>
        <p>{!! $page->content !!}</p>
    </div>
@endsection
