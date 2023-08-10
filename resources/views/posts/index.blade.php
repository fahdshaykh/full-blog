@extends('layout')

@section('content')
<div class="row">
    <div class="col-8">
    @forelse ($posts as $post)
        <p class="text-align-center">
            <h3>
                @if ($post->trashed())
                    <del>
                @endif
                <a class="{{ $post->trashed() ? 'text-muted' : '' }}"
                    href="{{ route('posts.show', ['post' => $post->id]) }}">{{ $post->title }}</a>
                @if ($post->trashed())
                    </del>
                @endif
            </h3>

            <p class="text-muted">
                Added {{ $post->created_at->diffForHumans() }}
                by <a href="{{ route('users.show', $post->user->id) }}">{{ $post->user->name }}</a>
            </p>

            @forelse ($post->tags as $tag)
                <x-tags :tagId="$tag->id" :tag="$tag->name"></x-tags>
            @empty
                <p>No tags found</p>
            @endforelse

            @if ($post->comments_count)
                <p>{{ $post->comments_count }} comments</p>
            @else
            <p>No comments yet!</p>
            @endif
 
            <div class="row">
                @auth
                    @can('update', $post)
                    <a href="{{ route('posts.edit', ['post' => $post->id]) }}"
                        class="btn btn-primary">
                        {{ __('Edit') }}
                    </a>
                    @endcan
                @endauth

                @auth
                    @if (!$post->trashed())
                        @can('delete', $post)
                            <form method="POST" class="fm-inline ml-2"
                            action="{{ route('posts.destroy', ['post' => $post->id]) }}">
                            @csrf
                            @method('DELETE')

                            <input type="submit" value="{{ __('Delete!') }}" class="btn btn-primary"/>
                            </form>
                        @endcan
                    @endif
                @endauth
            </div>

        </p>
    @empty
        <p>{{ __('No blog posts yet!') }}</p>
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
</div>    
@endsection('content')