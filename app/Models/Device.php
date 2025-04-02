<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\TankState;

class Device extends Model
{
    /** @use HasFactory<\Database\Factories\DeviceFactory> */
    use HasFactory;
    protected $fillable = ['name', 'board_id', 'tank_count'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'device_user');
    }

    public function tankStates()
    {
        return $this->hasMany(TankState::class);
    }

    public function tankConfigs()
    {
        return $this->hasMany(TankConfig::class);
    }
}
