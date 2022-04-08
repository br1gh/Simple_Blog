@extends('layouts.app')

@section('content')
    @auth
        @include('add-post-form')
    @endauth

    <div class="mt-4">{{$posts->links()}}</div>
    @foreach($posts as $post)
        @include('post-card', ['content' => $post->excerpt, 'button' => 'Read more', 'button_action' => "/post/$post->slug"])
    @endforeach
    <div class="mt-4">{{$posts->links()}}</div>
@endsection
