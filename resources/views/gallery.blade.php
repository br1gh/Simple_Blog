@foreach($files as $row => $chunk)
    <div class="row{{ $loop->last ? ' mb-0' : ' mb-4' }}">
        @foreach($chunk as $file)
            <div class="col-md-3 mb-4">
                <div class="position-relative ratio ratio-1x1">
                    <img src='{{asset("/storage/$file")}}' class="img-fluid" alt="Lights" style="object-fit: cover;">
                    <div class="position-absolute top-0 end-0 p-2 text-white">
                        <button class="copy-to-clipboard btn btn-info"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Copy to clipboard">
                            <i class="bi bi-clipboard text-white"></i>
                        </button>
                        <button onclick="deleteFilesFromGallery('{{$file}}')" class="btn btn-danger"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                            <i class="bi bi-trash text-white"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
        @if($loop->last)
            <div class="col-md-3 mb-4">
                <div class="position-relative ratio ratio-1x1">
                    <label for="newGalleryItem" class="d-flex justify-content-center align-items-center w-100 h-100">
                        <input type="file" id="newGalleryItem" class="visually-hidden" name="gallery[]" autofocus
                               multiple accept="image/*" onchange="addFilesToGallery()">
                        <span class="btn btn-primary">
                            <i class="bi bi-plus"></i> Add New
                        </span>
                    </label>
                </div>
            </div>
        @endif
    </div>
@endforeach

@if(!$files)
    <div class="row mb-0">
        <div class="col-md-3 mb-4">
            <div class="position-relative ratio ratio-1x1">
                <label for="newGalleryItem" class="d-flex justify-content-center align-items-center w-100 h-100">
                    <input type="file" id="newGalleryItem" class="visually-hidden" name="gallery[]" autofocus
                           multiple accept="image/*" onchange="addFilesToGallery()">
                    <span class="btn btn-primary">
                            <i class="bi bi-plus"></i> Add New
                    </span>
                </label>
            </div>
        </div>
    </div>
@endif
