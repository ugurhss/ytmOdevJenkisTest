<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupAnnouncement extends Model
{
    use HasFactory;


    protected $table = 'group_announcements';

    protected $fillable = [
        'group_id',
        'user_id',
        'title',
        'content',
    ];


    public function group()
    {
        return $this->belongsTo(Group::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
