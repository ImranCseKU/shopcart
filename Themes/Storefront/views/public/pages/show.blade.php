@extends('public.layout')

@section('title', $page->name)

@push('meta')
    <meta name="title" content="{{ $page->meta->meta_title ?: $page->name }}">
    <meta name="description" content="{{ $page->meta->meta_description }}">
    <meta name="twitter:card" content="summary">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $page->meta->meta_title ?: $page->name }}">
    <meta property="og:description" content="{{ $page->meta->meta_description }}">
    <meta property="og:image" content="{{ $logo }}">
@endpush

@section('content')
    <section class="custom-page-wrap clearfix">
        <div class="container">
            <div class="custom-page-content clearfix">
                {!! $page->body !!}
            </div>
        </div>
    </section>
@endsection
