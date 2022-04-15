@extends("layouts.app")

@section('content')
    @include('post-card', ['content' => $post->body, 'button' => '<- Go back', 'button_action' => "/"])
    @auth
        @if(Auth::user()->hasVerifiedEmail())
            @include('add-comment-form')
        @endauth
    @endauth
    @foreach($post->comments->reverse() as $comment)
        @include('comment-card')
    @endforeach
@endsection
