<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperBlogImage
 */
class BlogImage extends Model
{
    protected $fillable = [
        'image_path'
    ];

    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class, 'blog_id', 'id');
    }
}
