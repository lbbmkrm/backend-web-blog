<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    protected $table = 'profiles';
    protected $fillable = [
        'student_number',
        'name',
        'user_id',
        'university',
        'faculty',
        'study_program',
        'batch',
        'bio',
        'avatar',
        'phone'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
