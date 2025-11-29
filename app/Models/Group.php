<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'city_id',
        'university_id',
        'faculty_id',
        'department_id',
        'class_models_id',
        'groups_name'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }


    public function classModel()
    {
        return $this->belongsTo(ClassModel::class, 'class_models_id');
    }


    public function students()
    {
        return $this->belongsToMany(User::class, 'group_user');
    }


    public function announcements()
    {
        return $this->hasMany(GroupAnnouncement::class);
    }
}
