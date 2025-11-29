<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class University extends Model
{
   use HasFactory;

    protected $fillable = ['city_id', 'name'];


    public function city()
    {
        return $this->belongsTo(City::class);
    }


    public function faculties()
    {
        return $this->hasMany(Faculty::class);
    }
}
