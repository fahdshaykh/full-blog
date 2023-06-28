<div class="form-group">
    <label for="title">Title</label>
    <input class="form-control" id="title" type="text" name="title" value="{{ old('title', optional($post ?? null)->title) }}" id="">
</div>
@error('title')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror
<div class="form-grouup">
    <label for="content">Content</label>
    <textarea class="form-control" id="content" name="content" id="" cols="30" rows="10">{{ old('content', optional($post ?? null)->content) }}</textarea>
</div>
@error('content')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror