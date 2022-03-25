<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;

class Idea extends Model
{
    use HasFactory;

    protected $guarded = [];

    // public function getDocumentUrlAttribute($value)
    // {
    //     return Storage::url($value);
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function createdByUser()
    {
        return $this->user->full_name;
    }

    public function academic()
    {
        return $this->belongsTo(AcademicYear::class,'academic_year_id');
    }

}
