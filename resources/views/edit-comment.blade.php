@extends('layouts.app')

@section('content')
    <div class="card text-white border-info mt-4">
        <div class="card-header">Edit Comment</div>

        <div class="card-body">
            <form action="/post/{{$slug}}/comment/{{$comment->id}}/edit" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <label for="body" class="col-md-2 col-form-label text-md-end">Body</label>

                    <div class="col-md-8">
                            <textarea id="body" class="form-control text-white @error('excerpt') is-invalid @enderror"
                                      name="body" required> {{$comment->body}}</textarea>

                        @error('body')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="score" class="col-md-2 col-form-label text-md-end">Score</label>

                    <div class="col-md-8">
                        <input id="score" type="number" min="1" max="5" value="{{ $comment->score }}"
                               class="form-control text-white @error('score') is-invalid @enderror"
                               name="score" required>

                        @error('score')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-0">
                    <div class="col-md-8 offset-md-2">
                        <button type="submit" class="btn btn-info text-white w-100">
                            Edit Comment
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
