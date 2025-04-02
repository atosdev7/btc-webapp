<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TankConfig extends Model
{
    use HasFactory;
    public $incrementing = false; // Composite key strategy - disable default incrementing
    public $timestamps = false; // Disable timestamps

    protected $fillable = [
        'device_id',
        'tank_id',
        'control_mode',
        'target_temp',
        'pt100_cal',
        'degree_per_day',
        'update_flag',
    ];
}
