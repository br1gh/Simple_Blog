@extends('layouts.app')
@section('content')
    @include('user-details')

    @if($publishedPosts->count() > 0)
        <a href="#publishedPosts">
            <button id="publishedPosts" class="col-6 offset-3 btn-primary text-white rounded mt-4 h1">
                Posts
            </button>
        </a>

        @foreach($publishedPosts as $post)
            @include('post-card', ['content' => $post->excerpt, 'button' => 'Read more', 'button_action' => "/post/$post->slug"])
        @endforeach
    @endif

    @if($notPublishedPosts->count() > 0 && (Auth::user() && (Auth::id() == $user->id || (Auth::user()->isAdmin()))))
        <a href="#notPublishedPosts">
            <button id="notPublishedPosts" class="col-6 offset-3 btn-secondary text-white rounded mt-4 h1">
                Not published posts
            </button>
        </a>

        @foreach($notPublishedPosts as $post)
            @include('post-card', ['content' => $post->excerpt, 'button' => 'Read more', 'button_action' => "/post/$post->slug"])
        @endforeach
    @endif


    @if($comments->count() > 0)
        <a href="#comments">
            <button id="comments" class="col-6 offset-3 btn-success text-white rounded mt-4 h1">
                Comments
            </button>
        </a>

        @foreach($comments as $comment)
            @php
                $postColor = ($comment->post->deleted_at || $comment->post->user->banned_until > now())
                    ? 'danger' : ($comment->post->is_published ? 'primary' : 'secondary');
            @endphp
            <div class="card text-white border-{{$postColor}} mt-4">
                <div class="card-header">
                    <div class="row pt-2">
                        <h1 class="col-10">{{$comment->post->title}}</h1>
                        @if(Auth::id() != $comment->post->user_id)
                            <div class="col-2 d-flex justify-content-end">
                                <button class="report-post border-0 p-0" data-id="{{$comment->post->id}}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#report-post-modal" style="background-color: #282A36">
                                    <i class="bi bi-flag-fill text-warning h1"></i>
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <h4>by <a href="/user/{{$comment->post->user->username}}"
                                  class="text-{{$postColor}}">{{$comment->post->user->full_name}}</a></h4>
                    </div>
                </div>
                <div class="card-body mx-3">
                    <div class="row">
                        @include('comment-card')
                    </div>
                    <a href=/post/{{$comment->post->slug}}/>
                        <div class="row mt-5">
                            <button class="btn btn-{{$postColor}} btn-lg btn-block">
                                Show this post
                            </button>
                        </div>
                    </a>
                </div>
            </div>
            @include('components.modal.report-file', ['type' => 'post'], ['id' => 'report-post-modal'])
        @endforeach
    @endif

    @include('components.modal.report-file', ['type' => 'user'], ['id' => 'report-user-modal'])
    @include('components.modal.report-file', ['type' => 'comment'], ['id' => 'report-comment-modal'])

    <script>
        let url = '{{route('report')}}';
        let token = '{{csrf_token()}}';
    </script>
    <script src="{{asset('js/report-modal.js')}}"></script>
@endsection
