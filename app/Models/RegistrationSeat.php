<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationSeat extends Model
{
    use HasFactory;
    public $table = 'registration_seat';

    protected $fillable = [
        'registration_id',
        'seat_id'
    ];

    public function seat()
    {
        return $this->belongsTo(Seat::class, 'seat_id', 'id');
    }
}
