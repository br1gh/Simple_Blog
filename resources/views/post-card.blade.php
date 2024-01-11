<div class="card text-white border-primary mt-4">
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
                @else
{{--                    <a href="{{route('report', ['type'=>'post', 'id' => $post->id])}}" class="col-1 p-0 text-center">--}}
{{--                        <i class="bi bi-flag-fill text-warning h1"></i>--}}
{{--                    </a>--}}
                @endif
            @endauth
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            @php
                $path = "photos/$post->id/post_image/$post->post_image";
                if (Storage::disk('public')->exists($path))
                    echo "<div class='col-md-6 mb-3'><img src='/storage/$path' class='rounded img-fluid w-100' style='max-height: 300px; object-fit: cover;'></div>";
            @endphp
            <div class="col-md-6">
                <div class="mb-3">
                    <h5>created {{$post->created_at->diffForHumans()}}</h5>
                </div>

                @if($post->created_at != $post->updated_at)
                    <div class="mb-3">
                        <h5 class="text-muted">updated {{$post->updated_at->diffForHumans()}}</h5>
                    </div>
                @endif

                <div class="mb-3">
                    <h4>by <a href="/user/{{$post->user->username}}"
                              class="text-primary">{{$post->user->full_name}}</a></h4>
                </div>
            </div>
        </div>

        <div class="row">
            <p>{!!$content!!}</p>
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
