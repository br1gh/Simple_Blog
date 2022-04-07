@extends("layouts.app")

@section('content')
    @include('post-card', ['content' => $post->body, 'button' => '<- Go back', 'button_action' => "/"])
    @foreach($post->comments as $comment)
        @include('comment-card')
    @endforeach
@endsection
