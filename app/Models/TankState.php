<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TankState extends Model
{
    use HasFactory;

    protected $primaryKey = ['device_id', 'tank_id']; // Composite primary key
    public $incrementing = false; // Disable auto-incrementing since it's a composite key
    public $timestamps = false; // Disable timestamps

    protected $fillable = [
        'device_id',
        'tank_id',
        'sol_time',
        'current_temp',
        'solenoid',
        'max_rtd',
        'max_status',
    ];
}
