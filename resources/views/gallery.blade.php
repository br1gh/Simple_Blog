<div class="card border-white mt-4 mb-3">
    <a href="#gallery" class="text-decoration-none text-white text-center">
        <div class="card-header bg-primary bg-opacity-50 mt-6 h1" id="gallery">
            Gallery
        </div>
    </a>
    <div class="card-body bg-primary bg-opacity-10 pb-0">
        @php
            foreach ($files as $file)
            {
            echo "<div class='row'><img class='mx-auto mb-3 p-6 w-75' src='/storage/$file'></div>";
            }
        @endphp
    </div>
</div>
