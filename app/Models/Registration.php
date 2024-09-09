<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'fullname',
        'registration_number',
        'no_hp',
        'vehicle_type',
        'license_plate',
        'group_seat_id',
        'participant_id',
        'event_id',
        'event_slug',
        'is_scan',
        'is_vip',
        'is_valid',
        'token',
        'qty',
        'schedule_id',
        'price',
        'total',
        'counter',
        'account_bank_name',
    ];

    protected $with = ['groupSeat', 'seats', 'receipts'];

    public function groupSeat()
    {
        return $this->belongsTo(GroupSeat::class);
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function seats()
    {
        return $this->belongsToMany(Seat::class, 'registration_seat');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function getFullnameAttribute($value)
    {
        return ucwords($value);
    }

    public function getExpiredDatetime()
    {
        return $this->created_at->addMinutes(30);
    }

    public function getIsScanAttribute($value)
    {
        return $value ? 'Sudah Scan' : 'Belum Scan';
    }

    public function getIsValidAttribute($value)
    {
        return $value ? 'Terverifikasi' : 'Belum Terverifikasi';
    }

    public function setFullnameAttribute($value)
    {
        $this->attributes['fullname'] = strtolower($value);
    }

    public function getVehicleTypeAttribute($value)
    {
        return ucwords($value);
    }

    public function setVehicleTypeAttribute($value)
    {
        $this->attributes['vehicle_type'] = strtolower($value);
    }
}
