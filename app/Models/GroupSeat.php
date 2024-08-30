<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupSeat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quota',
        'event_id',
        'is_active',
        'schedule_id',
        'price'
    ];

    public function registration()
    {
        return $this->hasMany(Registration::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function getNameAttribute($value)
    {
        return ucwords($value);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }
}
