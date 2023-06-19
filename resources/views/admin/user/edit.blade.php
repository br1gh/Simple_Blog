@extends('layouts.admin.app')

@section('content')
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">
                    User
                </h4>
                <form method="post">
                    @csrf

                    <x-input.text
                        :label="'Username'"
                        :name="'username'"
                        :value="$obj->username"
                    />

                    <x-input.text
                        :label="'Full Name'"
                        :name="'full_name'"
                        :value="$obj->full_name"
                    />

                    <x-input.email
                        :label="'Email'"
                        :name="'email'"
                        :value="$obj->email"
                    />

                    <x-input.password
                        :label="'Password'"
                        :name="'password'"
                    />

                    <x-input.password
                        :label="'Password Confirmation'"
                        :name="'password_confirmation'"
                    />

                    @include('layouts.admin.edit.buttons')
                </form>
            </div>
        </div>
    </div>
@endsection
