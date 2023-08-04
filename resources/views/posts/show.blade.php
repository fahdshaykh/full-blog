@extends('layout')

@section('content')
<div class="row">
    <div class="col-8">
        
        <h3>{{ $post->title }}</h3>

        <p>{{ $post->content }}</p>

        @props([
            'name' => $post->user->name,
            'date' => $post->created_at->diffForHumans()
        ])

        <x-updated :name="$name" :date="$date" class="mb-4"/>

        @props([
            'message' => 'New Post!',
            'show' => now()->diffForHumans($post->created_at) < 5
        ])

        <x-badge type="success" :message="$message" class="mb-4"/>

        <h4>{{ __('Comments') }}</h4>

        @forelse ($post->comments as $comment)
            <p>
                {{ $comment->content }}
            </p>
            <p class="text-muted">
                {{ $comment->created_at->diffForHumans() }}
            </p>
        @empty
            <p>No comments yet!</p>
        @endforelse
    </div>
@endsection('content')