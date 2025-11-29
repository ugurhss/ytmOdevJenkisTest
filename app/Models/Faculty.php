<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
use HasFactory;

    protected $fillable = ['university_id', 'name'];


    public function university()
    {
        return $this->belongsTo(University::class);
    }


    public function departments()
    {
        return $this->hasMany(Department::class);
    }
}
