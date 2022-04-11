<div class="card text-white mt-4">
    <div class="card-body">
        <div class="row pt-2 mb-3">
            <h4 class="col-10">
                <a href="/?user={{$comment->user->username}}" class="text-primary">{{$comment->user->full_name}}</a>
            </h4>
            <a href="" class="col-1 p-0 text-center">
                <i class="bi bi-pencil-square text-info h1"></i>
            </a>
            <a href="" class="col-1 p-0 text-center">
                <i class="bi bi-trash text-danger h1"></i>
            </a>
        </div>

        <div class="row mb-3">
            <h5>{{$comment->created_at->diffForHumans()}}</h5>
        </div>

        <div class="row mb-3">
            {!!$comment->body!!}
        </div>
    </div>
</div>
