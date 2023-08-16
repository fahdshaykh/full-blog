<x-mail::message>
# Introduction

<p>Hi {{ $comment->commentable->user->name }}</p>

<p>
    Someone has commented on your blog post
    <a href="{{ route('posts.show', ['post' => $comment->commentable->id]) }}">
        {{ $comment->commentable->title }}
    </a>
</p>

<x-mail::button :url="''">
Button Text
</x-mail::button>

<p>
    "{{ $comment->content }}"
</p>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
