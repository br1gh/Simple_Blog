@extends('layouts.app')

@section('content')
    @if(Session::has('danger'))
        <div class="alert alert-danger mt-4">
            {{Session::get('danger')}}
        </div>
    @endif

    @auth
        @if(Auth::user()->hasVerifiedEmail() && !Auth::user()->isBanned())
            @include('add-post-form')
        @endif
    @endauth

    <div class="mt-4">{{$posts->links()}}</div>
    @foreach($posts as $post)
        @include('post-card', ['content' => $post->excerpt, 'button' => 'Read More', 'button_action' => "/post/$post->slug"])
    @endforeach
    <div class="mt-4">{{$posts->links()}}</div>
@endsection
