<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    /** @use HasFactory<\Database\Factories\DeviceFactory> */
    use HasFactory;
    protected $fillable = ['name', 'board_id', 'tank_count'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'device_user');
    }
}
