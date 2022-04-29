@extends('layouts.app')

@section('content')
    <div class="card text-white border-danger mt-4" style="background-color: #282a36">
        <div class="card-header">Delete Account</div>

        <div class="card-body">
            <div class="alert alert-danger" role="alert">
                {{ __('This action is permanent. All user data, posts and comments will be deleted.') }}
            </div>

            <form method="POST" action="/delete-account/">
                @csrf

                <div class="row mb-3">
                    <label for="password" class="col-md-2 col-form-label text-md-end">{{ __('Password') }}</label>

                    <div class="col-md-8">
                        <input id="password" type="password"
                               class="form-control text-white @error('password') is-invalid @enderror" name="password"
                               required autocomplete="current-password">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-8 offset-md-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="sure" id="sure" required>

                            <label class="form-check-label" for="sure">
                                {{ __('I am sure about deleting my account') }}
                            </label>
                        </div>

                    </div>
                </div>

                <div class="row mb-0">
                    <div class="col-md-6 offset-md-3">
                        <button type="submit" class="text-white btn btn-danger w-100">
                            Delete Account
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
