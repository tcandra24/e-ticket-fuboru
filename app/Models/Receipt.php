<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'file',
        'registration_id'
    ];

    public function getFileAttribute($value)
    {
        return asset('/storage/images/receipt/' . $value);
    }
}
