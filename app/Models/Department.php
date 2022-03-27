<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
    ];

    public function idea()
    {
        return $this->hasMany(Idea::class);
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function getCount($users)
    {
        foreach ($users as $key => $user) {
            dd($this->reactionCount($user));
            $count =+ ($this->commentCount($user) + $this->reactionCount($user));

            return $count ?? 0;
        }
    }

    public function commentCount($user)
    {
        return $user->comment ? $user->comment->count() : 0;
    }

    public function reactionCount($user)
    {
        return $user->reaction ? $user->reaction->count() : 0;
    }
}
