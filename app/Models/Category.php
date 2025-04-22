<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperCategory
 */
class Category extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'category_id', 'id');
    }
}
