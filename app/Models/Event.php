<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'slug', 'link', 'short_link', 'bitly_id', 'image', 'is_active', 'model_path'
    ];

    public function registration()
    {
        return $this->hasMany(Registration::class, 'event_id', 'id');
    }

    public function groupSeat()
    {
        return $this->hasMany(GroupSeat::class, 'event_id', 'id');
    }

    public function forms()
    {
        return $this->belongsToMany(FormField::class, 'form_event');
    }

    public function getNameAttribute($value)
    {
        return ucwords($value);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    public function getImageAttribute($value)
    {
        return asset('/storage/images/events/' . $value);
    }
}
