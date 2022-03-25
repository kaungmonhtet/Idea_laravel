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
}
