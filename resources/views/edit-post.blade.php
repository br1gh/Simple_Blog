@extends('layouts.app')

@section('content')
    <div class="card text-white border-info mt-4">

        <div class="card-header">Edit Post</div>

        <div class="card-body">
            <form action="/post/{{$post->slug}}/edit" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <label for="title" class="col-md-2 col-form-label text-md-end">Title</label>

                    <div class="col-md-8">
                        <input id="title" type="text"
                               class="form-control text-white @error('title') is-invalid @enderror" name="title"
                               value="{{$post->title}}" required autofocus>

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
                                      name="excerpt" required>{{ $post->excerpt }}</textarea>

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
                                      name="body" required>{{ $post->body }}</textarea>

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
                               class="form-control text-white @error('post_image') is-invalid @enderror"
                               name="post_image" autofocus>

                        @error('post_image')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-0">
                    <div class="col-md-8 offset-md-2">
                        <button type="submit" class="btn btn-info text-white w-100">
                            Edit Post
                        </button>
                    </div>
                </div>
            </form>

            <a href="/post/{{$post->slug}}/delete-post-image/">
                <div class="row mt-3 mb-0">
                    <div class="col-md-8 offset-md-2">
                        <button class="btn text-white bg-danger w-100">
                            Delete Post Image
                        </button>
                    </div>
                </div>
            </a>

            <a href="/post/{{$post->slug}}/delete-gallery/">
                <div class="row mt-3 mb-0">
                    <div class="col-md-8 offset-md-2">
                        <button class="btn text-white bg-danger w-100">
                            Delete Gallery
                        </button>
                    </div>
                </div>
            </a>

            <div class="card border-white mt-4 mb-3">
                <a href="#gallery" class="text-decoration-none text-white text-center">
                    <div class="card-header bg-primary bg-opacity-50 mt-6 h1" id="gallery">
                        Gallery
                    </div>
                </a>
                <div id="gallery-wrapper" class="card-body bg-primary bg-opacity-10">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        let timeout;

        $(document).ready(() => {
            fetchTable()
        });

        $(document).on('click', '.copy-to-clipboard', function () {
            let button = $(this);
            let path = '';
            try {
                path = $(this).parent().siblings().first().attr('src');
            } catch (e) {
            }

            if (window.isSecureContext && navigator.clipboard) {
                navigator.clipboard.writeText(path);
            } else {
                const textArea = document.createElement("textarea");
                textArea.value = path;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
            }

            button.html('<i class="bi bi-check text-white"></i>')
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                button.html('<i class="bi bi-clipboard text-white"></i>')
            }, 1000);
        })

        function fetchTable() {
            $('#gallery-wrapper').html(
                '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"></div></div>'
            )
            $.ajax({
                url: "{{route('fetch-gallery', ['id' => $post->id])}}",
                type: "GET",
                data: {
                    "_token": '{{ csrf_token() }}',
                },
                dataType: 'json',
                contentType: 'application/json',
                success: res => {
                    $('#gallery-wrapper').html(res);
                },
            });
        }

        function addFilesToGallery() {
            const fileInput = document.getElementById('newGalleryItem');
            const files = fileInput.files;

            if (files.length > 0) {
                const formData = new FormData();
                for (let i = 0; i < files.length; i++) {
                    formData.append('files[]', files[i]);
                }

                $.ajax({
                    url: "{{route('add-to-gallery', ['id' => $post->id])}}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: res => {
                        fetchTable()
                    },
                });
            }
        }

        function deleteFilesFromGallery(path) {
            $.ajax({
                url: "{{route('delete-from-gallery')}}",
                type: "POST",
                data: {"path": path},
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: res => {
                    fetchTable()
                },
            });
        }
    </script>
@endpush
