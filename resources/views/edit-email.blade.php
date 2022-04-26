@extends('layouts.app')

@section('content')
    <div class="card text-white border-info mt-4" style="background-color: #282a36">
        <div class="card-header">Edit email</div>

        <div class="card-body">
            <form method="POST" action="/edit-email/">
                @csrf

                <div class="row mb-3">
                    <label for="email" class="col-md-3 col-form-label text-md-end">{{ __('New Email Address') }}</label>

                    <div class="col-md-6">
                        <input id="email" type="email"
                               class="form-control text-white @error('email') is-invalid @enderror" name="email"
                               value="{{ old('email') }}" required autocomplete="email">

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="password"
                           class="col-md-3 col-form-label text-md-end">{{ __('Current Password') }}</label>

                    <div class="col-md-6">
                        <input id="password" type="password"
                               class="form-control text-white @error('password') is-invalid @enderror" name="password"
                               required autocomplete="new-password">

                        @error('password')
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
