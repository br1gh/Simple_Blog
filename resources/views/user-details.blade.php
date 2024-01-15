<div class="card text-white border-warning mt-4 mb-2">
    <div class="card-header bg-warning">
        <div class="row pt-2 mb-3">
            <h4 class="col-10 text-black h1">
                User Details
            </h4>
            <div class="col-2 d-flex justify-content-end">
                <button class="report-user border-0 p-0 h1" data-id="{{$user->id}}" data-bs-toggle="modal"
                        data-bs-target="#report-user-modal" style="background-color: #282A36">
                    <i class="bi bi-flag-fill bg-warning" style="color: #282A36"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row mb-3">
            <label for="username" class="col-md-2 col-form-label text-md-end">Username</label>

            <div class="col-md-8">
                <input id="username" type="text" class="form-control bg-body text-white" value="{{$user->username}}"
                       readonly>
            </div>
        </div>

        <div class="row mb-3">
            <label for="full_name" class="col-md-2 col-form-label text-md-end">Full Name</label>

            <div class="col-md-8">
                <input id="full_name" type="text" class="form-control bg-body text-white" value="{{$user->full_name}}"
                       readonly>
            </div>
        </div>

        <div class="row mb-3">
            <label for="email" class="col-md-2 col-form-label text-md-end">Email Address</label>

            <div class="col-md-8">
                <input id="email" type="text" class="form-control bg-body text-white"
                       value="{{$user->email}}" readonly>
            </div>
        </div>

        <div class="row mb-3">
            <label for="number-of-posts" class="col-md-2 col-form-label text-md-end">Posts</label>

            <div class="col-md-8">
                <input id="number-of-posts" type="text" class="form-control bg-body text-white"
                       value="{{$user->posts->count()}}" readonly>
            </div>
        </div>

        <div class="row mb-3">
            <label for="number-of-posts" class="col-md-2 col-form-label text-md-end">Comments</label>

            <div class="col-md-8">
                <input id="number-of-posts" type="text" class="form-control bg-body text-white"
                       value="{{$user->comments->count()}}" readonly>
            </div>
        </div>

        <div class="row">
            <label for="joined" class="col-md-2 col-form-label text-md-end">Joined</label>

            <div class="col-md-8">
                <input id="joined" type="text" class="form-control bg-body text-white"
                       value="{{$user->created_at->diffForHumans()}}" readonly>
            </div>
        </div>

        @auth
            @if(Auth::user()->id == $user->id)
                <a href="/edit-details/">
                    <div class="row mt-3 mb-0">
                        <div class="col-md-8 offset-md-2">
                            <button class="btn text-white bg-info w-100">
                                Edit User Details
                            </button>
                        </div>
                    </div>
                </a>

                <a href="/edit-email/">
                    <div class="row mt-3 mb-0">
                        <div class="col-md-8 offset-md-2">
                            <button class="btn text-white bg-info w-100">
                                Change Email Address
                            </button>
                        </div>
                    </div>
                </a>

                <a href="/edit-password/">
                    <div class="row mt-3 mb-0">
                        <div class="col-md-8 offset-md-2">
                            <button class="btn text-white bg-info w-100">
                                Change Password
                            </button>
                        </div>
                    </div>
                </a>

                <a href="/delete-account/">
                    <div class="row mt-3 mb-0">
                        <div class="col-md-8 offset-md-2">
                            <button class="btn text-white bg-danger w-100">
                                Delete Account
                            </button>
                        </div>
                    </div>
                </a>
            @endif
        @endauth
    </div>
</div>
