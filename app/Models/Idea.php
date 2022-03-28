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

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    public function createdByUser()
    {
        return $this->user->full_name;
    }

    public function academic()
    {
        return $this->belongsTo(AcademicYear::class,'academic_year_id');
    }

    public function scopeFilter($query,$filters)
    {
        $filters->apply($query);
    }

    public function isOwner()
    {
        return $this->user_id == auth()->id() ? true : false;
    }

    public function likeCount()
    {
        return $this->reactions()->where('up_down',1)->count();
    }    

    public function unlikeCount()
    {
        return $this->reactions()->where('up_down',0)->count();
    }    

    public function commentCount()
    {
        return $this->comments()->count();
    }

    public function department()
    {
        return $this->belongsTo(Department::class)->orderBy('description', 'asc');
    }

}
