<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin IdeHelperBlog
 */
class Blog extends Model
{
    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'content',
        'slug',
        'excerpt',
        'thumbnail',
        'tags'
    ];

    public function isLiked(): bool
    {
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return false;
        }
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'blog_id', 'id');
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes', 'blog_id', 'user_id');
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class, 'blog_id', 'id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,
            'blog_tags',
            'blog_id',
            'tag_id'
        )->using(BlogTag::class);
    }
}
