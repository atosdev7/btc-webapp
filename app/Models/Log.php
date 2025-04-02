<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Log extends Model
{
    use HasFactory;
    public $timestamps = false; // Disable timestamps

    protected $fillable = [
        'device_id',
        'tank_id',
        'time',
        'current_temp',
        'solenoid',
        'max_rtd',
        'max_status',
    ];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
