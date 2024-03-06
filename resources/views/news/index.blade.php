@extends('layouts.app')

@section('title')
    Site News
@endsection

@section('content')
    {!! breadcrumbs(['Site News' => 'news']) !!}
    <h1>Site News</h1>
    @if (count($newses))
        {!! $newses->render() !!}
        <div class="row justify-content-center">
            @foreach ($newses as $news)
                @include('news._news_preview', ['news' => $news])
            @endforeach
        </div>

        {!! $newses->render() !!}
    @else
        <div>No news posts yet.</div>
    @endif
@endsection
