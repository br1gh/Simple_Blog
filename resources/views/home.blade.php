@extends('layouts.app')

@section('content')
    <div class="card text-white border-warning mt-4" style="background-color: #282a36">

        <div class="card-header">{{ __('Dashboard') }}</div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            {{ __('You are logged in!') }}
        </div>
    </div>
@endsection
