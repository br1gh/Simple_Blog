@extends('layouts.app')

@section('content')
    <div class="card text-white border-info mt-4" style="background-color: #282a36">
        <div class="card-header">Change Password</div>

        <div class="card-body">
            <form method="POST" action="/edit-password/">
                @csrf

                <div class="row mb-3">
                    <label for="password" class="col-md-3 col-form-label text-md-end">{{ __('New Password') }}</label>

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

                <div class="row mb-3">
                    <label for="password-confirm"
                           class="col-md-3 col-form-label text-md-end">{{ __('Confirm New Password') }}</label>

                    <div class="col-md-6">
                        <input id="password-confirm" type="password" class="form-control text-white"
                               name="password_confirmation" required autocomplete="new-password">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="old_password" class="col-md-3 col-form-label text-md-end">{{ __('Current Password') }}</label>

                    <div class="col-md-6">
                        <input id="old_password" type="password"
                               class="form-control text-white @error('old_password') is-invalid @enderror" name="old_password"
                               required autocomplete="current-password">

                        @error('old_password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-0">
                    <div class="col-md-6 offset-md-3">
                        <button type="submit" class="btn btn-info w-100">
                            Change Password
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
