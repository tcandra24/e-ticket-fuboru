<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Participant extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'no_hp',
        'password',
        'verify_email_token',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
    public function getNameAttribute($value)
    {
        return ucwords(strtolower($value));
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }
}
