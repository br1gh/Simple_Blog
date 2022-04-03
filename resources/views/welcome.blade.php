@extends('layouts.app')

@section('content')
    {{$posts->links()}}
    @foreach($posts as $post)
        <div class="card text-white mt-4">
            <div class="card-header mt-9">
                <h1 class="mb-0">{{$post->title}}</h1>
            </div>

            <div class="card-body">
                <div class="row mb-3">
                    <h5>{{$post->user->created_at->diffForHumans()}}</h5>
                </div>

                <div class="row mb-3">
                    <h4>by <a class="text-primary">{{$post->user->name}}</a></h4>
                </div>

                <div class="row mb-3">
                    {!!$post->excerpt!!}
                </div>

                <div class="row m-0">
                    <button class="btn btn-primary btn-lg btn-block">
                        Read more
                    </button>
                </div>
            </div>
        </div>
    @endforeach
    <div class="mt-4">{{$posts->links()}}</div>
@endsection
