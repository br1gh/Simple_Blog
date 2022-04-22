@extends('layouts.app')
@section('content')
    @include('user-details')

    @if($user->posts->count() > 0)
        <a href="#posts">
            <button id="posts" class="col-6 offset-3 btn-primary text-white rounded mt-4 h1">
                Posts
            </button>
        </a>

        @foreach($user->posts->reverse() as $post)
            @include('post-card', ['content' => $post->excerpt, 'button' => 'Read more', 'button_action' => "/post/$post->slug"])
        @endforeach
    @endif

    @if($user->comments->count() > 0)
        <a href="#comments">
            <button id="comments" class="col-6 offset-3 btn-success text-white rounded mt-4 h1">
                Comments
            </button>
        </a>

        @foreach($user->comments->reverse() as $comment)
            <div class="card text-white border-primary mt-4">
                <div class="card-header">
                    <div class="row pt-2">
                        <h1 class="col-10">{{$comment->post->title}}</h1>
                    </div>

                    <div class="mb-3">
                        <h4>by <a href="/user/{{$comment->post->user->username}}"
                                  class="text-primary">{{$comment->post->user->full_name}}</a></h4>
                    </div>
                </div>
                <div class="card-body mx-3">
                    <div class="row">
                        @include('comment-card')
                    </div>
                    <a href=/post/{{$comment->post->slug}}/>
                        <div class="row mt-5">
                            <button class="btn btn-primary btn-lg btn-block">
                                Show this post
                            </button>
                        </div>
                    </a>
                </div>
            </div>
        @endforeach
    @endif
@endsection
