<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Device;
use App\Models\TankConfig;
use Inertia\Inertia;

class TankConfigController extends Controller
{
    public function index(Request $request)
    {
        $device_id = $request->query('device_id');
        
        if ($device_id == null) {
            $selectedDeviceId = $request->session()->get('selected_device_id', null);
            if ($selectedDeviceId) {
                $device_id = $selectedDeviceId;
                return redirect()->route('tankconfigs.index', ['device_id' => $device_id]);
            } else {
                try {
                    $device_id = Auth::user()->devices()->first()->id; // Default to the first device if no ID is provided
                    return redirect()->route('tankconfigs.index', ['device_id' => $device_id]);
                } catch (\Exception $e) {
                    return Inertia::render('TankConfigs', [
                        'devices' => [],
                        'selectedDeviceId' => null,
                        'tankConfigs' => [],
                        'error' => 'No devices found.',
                    ]);
                }
            }
        }

        $devices = Auth::user()->devices()->get();
        $device = Auth::user()->devices()->find($device_id);

        if (!$device) {
            return Inertia::render('TankConfigs', [
                'devices' => [],
                'selectedDeviceId' => null,
                'tankConfigs' => [],
                'error' => 'No devices found.',
            ]);
        }

        $request->session()->put('selected_device_id', $device_id);

        $tankConfigs = $device->tankConfigs()->get();
        if ($tankConfigs->isEmpty()) {
            return Inertia::render('TankConfigs', [
                'devices' => $devices,
                'selectedDeviceId' => $device_id,
                'tankConfigs' => [],
                'error' => 'No tank config found for this device.',
            ]);
        }

        return Inertia::render('TankConfigs', [
            'devices' => $devices,
            'selectedDeviceId' => $device_id,
            'tankConfigs' => $tankConfigs,
        ]);
    }

    public function update(Request $request, $tank_config)
    {
        $tankConfig = TankConfig::findOrFail($tank_config);

        $devices = Auth::user()->devices()->get();
        
        if (!$devices->contains('id', $tankConfig->device_id)) {
            return redirect()->route('tankconfigs.index')->with('error', 'Unauthorized');
        }

        $validatedData = $request->validate([
            'control_mode' => 'required|numeric',
            'target_temp' => 'required|numeric',
            'pt100_cal' => 'required|numeric',
            'degree_per_day' => 'required|numeric',
        ]);

        $tankConfig->update(array_merge($validatedData, ['update_flag' => true]));
        
        return redirect()->route('tankconfigs.index')->with('success', 'Device updated.');
    }
}
