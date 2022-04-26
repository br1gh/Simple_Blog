@extends('layouts.app')

@section('content')
    <div class="card text-white border-info mt-4" style="background-color: #282a36">
        <div class="card-header">Edit details</div>

        <div class="card-body">
            <form method="POST" action="/user/{{$user->username}}/edit-details/">
                @csrf

                <div class="row mb-3">
                    <label for="username" class="col-md-3 col-form-label text-md-end">{{ __('Username') }}</label>

                    <div class="col-md-6">
                        <input id="username" type="text"
                               class="form-control text-white @error('username') is-invalid @enderror" name="username"
                               value="{{ $user->username }}" required autocomplete="username" autofocus>

                        @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="full_name" class="col-md-3 col-form-label text-md-end">{{ __('Full Name') }}</label>

                    <div class="col-md-6">
                        <input id="full_name" type="text"
                               class="form-control text-white @error('full_name') is-invalid @enderror" name="full_name"
                               value="{{ $user->full_name }}" required autocomplete="full_name" autofocus>

                        @error('full_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-0">
                    <div class="col-md-6 offset-md-3">
                        <button type="submit" class="btn btn-info w-100">
                            Edit details
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
