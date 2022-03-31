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
        $count = 0;
        foreach ($users as $key => $user) {
            $count += ($this->ideaCount($user) || $this->commentCount($user) || $this->reactionCount($user)) ? 1 : 0 ;

        }
        return $count;
    }

    public function contributorCount()
    {
        return $this->getCount($this->user);
    }    

    public function uncontributorCount()
    {
        return $this->user->count() - $this->getCount($this->user);
    }

    public function commentCount($user)
    {
        return $user->comment ? true : false;
    }    

    public function ideaCount($user)
    {
        return !$user->ideas->isEmpty() ? true : false;
    }

    public function reactionCount($user)
    {
        return $user->reaction ? true : false;
    }
}
