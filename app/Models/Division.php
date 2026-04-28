<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * This prevents the MassAssignmentException error.
     */
    protected $fillable = [
        'name',
    ];

    /**
     * A Division can have many Users.
     * This establishes the relationship to the User model.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
