<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bookmark extends Model
{
    protected $fillable = ['user_id', 'blog_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class, 'blog_id', 'id');
    }
}
