<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Follow extends Model
{
    protected $fillable = [
        'follower_id',
        'followed_id'
    ];

    public function follower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'follower_id', 'id');
    }

    public function followed(): BelongsTo
    {
        return $this->belongsTo(User::class, 'followed_id', 'id');
    }
}
