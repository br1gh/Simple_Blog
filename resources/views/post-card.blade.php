<div class="card text-white mt-4">
    <div class="card-header mt-6">

        <div class="row pt-2">
            <h1 class="col-10">{{$post->title}}</h1>
            @auth
                @if(Auth::user()->id == $post->user->id)
                    <a href="/post/{{$post->slug}}/edit" class="col-1 p-0 text-center">
                        <i class="bi bi-pencil-square text-info h1"></i>
                    </a>
                    <a href="/post/{{$post->slug}}/delete" class="col-1 p-0 text-center">
                        <i class="bi bi-trash text-danger h1"></i>
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <div class="card-body">
        <div class="row mb-3">
            <h5>{{$post->created_at->diffForHumans()}}</h5>
        </div>

        <div class="row mb-3">
            <h4>by <a href="/?user={{$post->user->username}}" class="text-primary">{{$post->user->full_name}}</a></h4>
        </div>

        <div class="row mb-3">
            {!!$content!!}
        </div>

        <a href="{{$button_action}}">
            <div class="row m-0">
                <button class="btn btn-primary btn-lg btn-block">
                    {{$button}}
                </button>
            </div>
        </a>
    </div>
</div>
