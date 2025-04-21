<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Support\Facades\Hash;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',  // Add 'password' to the fillable array
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',  // Hide the password in the JSON response
    ];

    /**
     * Hash the password when creating or updating a user.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        // Automatically hash the password before saving the user
        static::creating(function ($user) {
            $user->password = Hash::make($user->password);  // Hash the password
        });

        static::updating(function ($user) {
            $user->password = Hash::make($user->password);  // Hash the password before updating
        });
    }
}
