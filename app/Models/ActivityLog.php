<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'event',
        'description',
        'actor_id',
        'actor_name',
        'subject_type',
        'subject_id',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function subject()
    {
        return $this->morphTo(null, 'subject_type', 'subject_id');
    }
}
