@extends('layouts.app')

@section('content')
    <div class="card text-white mt-4">

        <div class="card-header">Edit post</div>

        <div class="card-body">
            <form action="/post/{{$post->slug}}/edit" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <label for="title" class="col-md-2 col-form-label text-md-end">Title</label>

                    <div class="col-md-8">
                        <input id="title" type="text"
                               class="form-control text-white @error('title') is-invalid @enderror" name="title"
                               value="{{$post->title}}" required autocomplete="title" autofocus>

                        @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="excerpt" class="col-md-2 col-form-label text-md-end">Excerpt</label>

                    <div class="col-md-8">
                            <textarea id="excerpt"
                                      class="form-control text-white @error('excerpt') is-invalid @enderror"
                                      name="excerpt" required
                                      autocomplete="excerpt">{{ $post->excerpt }}</textarea>

                        @error('excerpt')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="body" class="col-md-2 col-form-label text-md-end">Body</label>

                    <div class="col-md-8">
                            <textarea id="body" class="form-control text-white @error('body') is-invalid @enderror"
                                      name="body" required autocomplete="body">{{ $post->body }}</textarea>

                        @error('body')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-0">
                    <div class="col-md-8 offset-md-2">
                        <button type="submit" class="btn btn-info w-100">
                            Edit post
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
