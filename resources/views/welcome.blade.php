@extends('layouts.app')

@section('content')
    {{$posts->links()}}
    @foreach($posts as $post)
        @include('post-card', ['content' => $post->excerpt, 'button' => 'Read more', 'button_action' => "/post/$post->slug"])
    @endforeach
    <div class="mt-4">{{$posts->links()}}</div>
@endsection
