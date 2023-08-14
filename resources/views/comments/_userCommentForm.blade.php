@auth

<div class="mb-2 mt-3">
    <form method="POST" action="{{ route('users.comments.store', ['user' => $user->id]) }}" enctype="multipart/form-data">
        @csrf

        <div class="form-grouup">
            <textarea class="form-control" id="content" name="content" id="">{{ old('content') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-block mt-2">Add Comment!</button>
    </form>
</div>

@error('content')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror

@else

<a href="{{ route('login') }}">Sign-in</a> to post comments!

@endauth

<hr>