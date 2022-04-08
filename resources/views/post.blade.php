@extends("layouts.app")

@section('content')
    @include('post-card', ['content' => $post->body, 'button' => '<- Go back', 'button_action' => "/"])
    @auth
        @include('add-comment-form')
    @endauth
    @foreach($post->comments->reverse() as $comment)
        @include('comment-card')
    @endforeach
@endsection
