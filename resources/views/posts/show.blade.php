@extends('layout')

@section('content')
<div class="row">
    <div class="col-8">
        
        <h3>{{ $post->title }}</h3>

        <p>{{ $post->content }}</p>

        <h4>{{ __('Comments') }}</h4>

        @forelse ($post->comments as $comment)
            <p>{{ $comment->content }}</p>
            <p class="text-muted">{{ $comment->created_at->diffForHumans() }}</p>
        @empty
            <p>No comments yet!</p>
        @endforelse
    </div>
@endsection('content')