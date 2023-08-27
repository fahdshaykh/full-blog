<?php

namespace App\Models;

use App\Scopes\LatestScope;
use App\Traits\Taggable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes, Taggable;

    protected $fillable = [
        'content',
        'blog_post_id',
        'user_id'
    ];

    protected $hidden = [
        'deleted_at',
        'commentable_type',
        'commentable_id',
        'user_id'
    ];

    // public function commentable()
    // {
    //     return $this->morphTo(BlogPost::class);
    // }
    public function commentable()
    {
        return $this->morphTo();
    }

    //the function name blogPost change blog_post_id actually laravel its handle by adding suffix _id after function name;
    public function blogPost()
    {
        return $this->belongsTo(BlogPost::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // created taggable traits
    // public function tags()
    // {
    //     return $this->morphToMany(Tag::class, 'taggable')->withTimestamps();
    // }

    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    // public static function boot()
    // {
    //     parent::boot();
        
    //     static::creating(function(Comment $comment) {
    //         if ($comment->commentable_type === BlogPost::class) {
    //             Cache::tags(['blog-post'])->forget("blog-post-{$comment->blog_post_id}");
    //             Cache::tags(['blog-post'])->forget('mostCommentetd');
    //         }
    //     });

    //     // static::addGlobalScope(new LatestScope);
    // }
}
