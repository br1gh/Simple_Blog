<div class="card text-white mt-4">
    <div class="card-body">
        <div class="row mb-3">
            <h4>
                <a href="/?user={{$comment->user->username}}" class="text-primary">{{$comment->user->full_name}}</a>
            </h4>
        </div>

        <div class="row mb-3">
            <h5>{{$comment->created_at->diffForHumans()}}</h5>
        </div>

        <div class="row mb-3">
            {!!$comment->body!!}
        </div>
    </div>
</div>
