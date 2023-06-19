<div class="card text-white border-success mt-4 p-0">
    <div class="card-header">
        <div class="row pt-2 mb-3">
            <h4 class="col-10">
                <a href="/user/{{$comment->user->username}}/" class="text-success">{{$comment->user->full_name}}</a>
            </h4>
            @auth
                @if(Auth::user()->id == $comment->user->id)
                    <a href="/post/{{$post->slug}}/comment/{{$comment->id}}/edit" class="col-1 p-0 text-center">
                        <i class="bi bi-pencil-square text-info h1"></i>
                    </a>
                    <a href="/post/{{$post->slug}}/comment/{{$comment->id}}/delete" class="col-1 p-0 text-center">
                        <i class="bi bi-trash text-danger h1"></i>
                    </a>
                @endif
{{--                <a href="{{route('report', ['type'=>'comment', 'id' => $comment->id])}}" class="col-1 p-0 text-center">--}}
{{--                    <i class="bi bi-flag-fill text-warning h1"></i>--}}
{{--                </a>--}}
            @endauth
        </div>

        <div class="row mb-3">
            <h5>{{$comment->created_at->diffForHumans()}}</h5>
        </div>

        <div class="row mb-3">
            <h2 class="text-success">{{str_repeat('★',$comment->score)}}{{str_repeat('☆',5-$comment->score)}}</h2>
        </div>

        <div class="row mb-3">
            <p>{!!$comment->body!!}</p>
        </div>
    </div>
</div>
