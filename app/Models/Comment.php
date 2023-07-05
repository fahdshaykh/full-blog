<?php

namespace App\Models;

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
}
