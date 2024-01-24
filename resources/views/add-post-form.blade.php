<div class="card text-white border-primary mt-4">

    <div class="card-header">Add Post</div>

    <div class="card-body">
        <form action="/" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row mb-3">
                <label for="title" class="col-md-2 col-form-label text-md-end">Title</label>

                <div class="col-md-8">
                    <input id="title" type="text"
                           class="form-control text-white @error('title') is-invalid @enderror" name="title"
                           value="{{ old('title') }}" required autofocus>

                    @error('title')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="slug" class="col-md-2 col-form-label text-md-end">Slug</label>

                <div class="col-md-8">
                    <input id="slug" type="text"
                           class="form-control text-white @error('slug') is-invalid @enderror" name="slug"
                           value="{{ old('slug') }}" required autofocus>

                    @error('slug')
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
                                      name="excerpt" required>{{ old('excerpt') }}</textarea>

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
                                      name="body" required>{{ old('body') }}</textarea>

                    @error('body')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="post_image" class="col-md-2 col-form-label text-md-end">Post Image</label>

                <div class="col-md-8">
                    <input id="post_image" type="file"
                           class="form-control text-white @error('post_image') is-invalid @enderror" name="post_image"
                           autofocus>

                    @error('post_image')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="gallery" class="col-md-2 col-form-label text-md-end">Gallery Images</label>

                <div class="col-md-8">
                    <input id="gallery" type="file"
                           class="form-control text-white @error('gallery') is-invalid @enderror" name="gallery[]"
                           autofocus multiple>

                    @error('gallery')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="post_image" class="col-md-2 col-form-label text-md-end">Published</label>

                <div class="col-md-8">
                    <div class="form-check form-switch">
                        <input type="hidden" name="is_published" value="0">
                        <input id="is_published" name="is_published" class="form-check-input" type="checkbox"
                               value="1" style="width: 50px; height: 25px">
                    </div>
                </div>
            </div>

            <div class="row mb-0">
                <div class="col-md-8 offset-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        Add Post
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
