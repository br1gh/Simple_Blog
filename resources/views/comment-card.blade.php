<div class="card text-white border-success mt-4 p-0">
    <div class="card-header mt-6">
        <div class="row pt-2 mb-3">
            <h4 class="col-10">
                <a href="/user/{{$comment->user->username}}/" class="text-success">{{$comment->user->full_name}}</a>
            </h4>
            @auth
                <div class="col-2 d-flex justify-content-end">
                    @if(Auth::user()->id == $comment->user->id)
                        <a href="/post/{{$post->slug}}/comment/{{$comment->id}}/edit" style="margin-right: 10px">
                            <i class="bi bi-pencil-square text-info h1"></i>
                        </a>
                        <a href="/post/{{$post->slug}}/comment/{{$comment->id}}/delete">
                            <i class="bi bi-trash text-danger h1"></i>
                        </a>
                    @else
                        <button class="report-comment border-0 p-0" data-id="{{$comment->id}}" data-bs-toggle="modal"
                                data-bs-target="#report-comment-modal" style="background-color: #282A36">
                            <i class="bi bi-flag-fill text-warning h1"></i>
                        </button>
                    @endif
                </div>
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
