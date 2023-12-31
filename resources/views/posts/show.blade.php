@extends('layout')

@section('content')
<div class="row">
    <div class="col-8">
        @if ($post->image)
        <div style="background-image: url('{{ $post->image->url() }}'); min-height: 500px; color:white; text-align:center; background-attachment:fixed;">
            <h1 style="padding-top: 100px; text-shadow: 1px 2px #000">
        @else
            <h1>
        @endif
        @props([
            'message' => 'New Post!',
            'show' => now()->diffForHumans($post->created_at) < 5
        ])

        <h4><x-badge type="success" :message="$message" class="mb-4"/></h4>
        <h3>{{ $post->title }}</h3>

        @if ($post->image)
            </h1>
        </div>
        @else
            </h1>
        @endif
        <p>{{ $post->content }}</p>


        @props([
            'name' => $post->user->name,
            'date' => $post->created_at->diffForHumans()
        ])

        <x-updated :name="$name" :date="$date" class="mb-4"/>

        @forelse ($post->tags as $tag)
            <x-tags :tagId="$tag->id" :tag="$tag->name"></x-tags>
        @empty
            <p>No tags found</p>
        @endforelse
        
        {{-- <p>Currently read by {{ $counter }} people</p> --}}
        <p>{{ trans_choice('messages.people.reading', $counter) }}</p>

        <h4>{{ __('Comments') }}</h4>

        @include('comments._form')

        @forelse ($post->comments as $comment)
            <p>
                {{ $comment->content }}

                @forelse ($comment->tags as $tag)
                    <x-tags :tagId="$tag->id" :tag="$tag->name"></x-tags>
                @empty
                    <p>No tags found</p>
                @endforelse
            </p>
            <p class="text-muted">
               Added {{ $comment->created_at->diffForHumans() }} by {{ $comment->user->name }}
            </p>
        @empty
            <p>No comments yet!</p>
        @endforelse
    </div>
    <div class="col-4">
        <div class="container">
            <div class="row">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                      <h5 class="card-title">Most Commented</h5>
                      <h6 class="card-subtitle mb-2 text-muted">What people are currently talking about.</h6>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach ($mostCommented as $post)
                            <li class="list-group-item">
                                <a href="{{ route('posts.show', ['post' => $post->id]) }}">
                                    {{ $post->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="row mt-4">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                      <h5 class="card-title">Most Active</h5>
                      <h6 class="card-subtitle mb-2 text-muted">Users with most posts written.</h6>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach ($mostActive as $user)
                            <li class="list-group-item">
                                {{ $user->name }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="row mt-4">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                      <h5 class="card-title">Most Active Last Month</h5>
                      <h6 class="card-subtitle mb-2 text-muted">Users with most most active last month.</h6>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach ($mostActiveLastMonth as $user)
                            <li class="list-group-item">
                                {{ $user->name }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection('content')