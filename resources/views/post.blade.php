@extends("layouts.app")

@section('content')
    @include('post-card', ['content' => $post->body, 'button' => '← Go Back', 'button_action' => "/"])
    @auth
        @if(Auth::user()->hasVerifiedEmail() && !Auth::user()->isBanned())
            @include('add-comment-form')
        @endauth
    @endauth
    @foreach($comments as $comment)
        @include('comment-card')
    @endforeach
@endsection
