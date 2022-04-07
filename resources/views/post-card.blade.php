<div class="card text-white mt-4">
    <div class="card-header mt-9">
        <h1 class="mb-0">{{$post->title}}</h1>
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
