<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date',
        'event_id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
