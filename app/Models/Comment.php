<?php

namespace App\Models;

use App\Scopes\LatestScope;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'content',
        'blog_post_id'
    ];

    //the function name blogPost change blog_post_id actually laravel its handle by adding suffix _id after function name;
    public function blogPost()
    {
        return $this->belongsTo(BlogPost::class);
    }

    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public static function boot()
    {
        parent::boot();

        // static::addGlobalScope(new LatestScope);
    }
}
