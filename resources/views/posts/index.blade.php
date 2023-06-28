@extends('layout')

@section('content')
<div class="row">
    <div class="col-8">
    @forelse ($posts as $post)
        <p>
            <h3>
                
                <a class="text-muted"
                    href="{{ route('posts.show', ['post' => $post->id]) }}">{{ $post->title }}</a>
                
            </h3>

            @if ($post->comments_count)
                <p>{{ $post->comments_count }} comments</p>
            @else
            <p>No comments yet!</p>
            @endif


                    <div class="row">
                        <a href="{{ route('posts.edit', ['post' => $post->id]) }}"
                            class="btn btn-primary">
                            {{ __('Edit') }}
                        </a>
    
                {{-- @cannot('delete', $post)
                    <p>You can't delete this post</p>
                @endcannot --}}
    
                        <form method="POST" class="fm-inline ml-2"
                            action="{{ route('posts.destroy', ['post' => $post->id]) }}">
                            @csrf
                            @method('DELETE')
    
                            <input type="submit" value="{{ __('Delete!') }}" class="btn btn-primary"/>
                        </form>
                    </div>
        </p>
    @empty
        <p>{{ __('No blog posts yet!') }}</p>
    @endforelse
    </div>

</div>    
@endsection('content')