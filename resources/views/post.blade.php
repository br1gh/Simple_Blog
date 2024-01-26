@extends("layouts.app")

@section('content')
    @include('post-card', ['content' => $post->body, 'button' => '← Go Back', 'button_action' => "/"])
    @auth
        @if(Auth::user()->hasVerifiedEmail() && !Auth::user()->isBanned() && !$userHasComment)
            @include('add-comment-form')
        @endauth
    @endauth
    @foreach($comments as $comment)
        @include('comment-card')
    @endforeach

    @include('components.modal.report-file', ['type' => 'comment'], ['id' => 'report-comment-modal'])

    <script>
        let url = '{{route('report')}}';
        let token = '{{csrf_token()}}';
    </script>
    <script src="{{asset('js/report-modal.js')}}"></script>
@endsection
