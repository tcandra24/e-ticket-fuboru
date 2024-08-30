<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'group_seat_id'
    ];

    public function groupSeat()
    {
        return $this->belongsTo(GroupSeat::class);
    }

    public function registrations()
    {
        return $this->belongsToMany(Registration::class, 'registration_seat');
    }
}
