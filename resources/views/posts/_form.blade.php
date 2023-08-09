<div class="form-group">
    <label>{{ __('Title') }}</label>
    <input type="text" name="title" class="form-control"
        value="{{ old('title', $post->title ?? null) }}"/>
</div>
@error('title')
    <div class="text-danger">{{ $message }}</div>
@enderror

<div class="form-group">
    <label>{{ __('Content') }}</label>
    <input type="text" name="content" class="form-control"
        value="{{ old('content', $post->content ?? null) }}"/>
</div>
@error('content')
    <div class="text-danger">{{ $message }}</div>
@enderror

<div class="form-group">
    <label>{{ __('Thumbnail') }}</label>
    <input type="file" name="thumbnail" class="form-control-file"/>
</div>
@error('thumbnail')
    <div class="text-danger">{{ $message }}</div>
@enderror