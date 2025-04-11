<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Device;
use App\Models\TankState;
use App\Models\User;

class TankStateController extends Controller
{
    public function index(Request $request)
    {
        $device_id = $request->query('device_id');
        
        if ($device_id == null) {
            $selectedDeviceId = $request->session()->get('selected_device_id', null);
            if ($selectedDeviceId) {
                return redirect()->route('dashboard', ['device_id' => $selectedDeviceId]);
            } else {
                try {
                    $device_id = Auth::user()->devices()->first()->id; // Default to the first device if no ID is provided
                    return redirect()->route('dashboard', ['device_id' => $device_id]);
                } catch (\Exception $e) {
                    return Inertia::render('Dashboard', [
                        'devices' => [],
                        'tankStates' => [],
                        'selectedDeviceId' => null,
                        'error' => 'No devices found.',
                    ]);
                }
            }
        }

        $devices = Auth::user()->devices()->get();
        $device = Auth::user()->devices()->find($device_id);

        if (!$device) {
            return Inertia::render('Dashboard', [
                'devices' => $devices,
                'tankStates' => [],
                'selectedDeviceId' => null,
                'error' => 'No devices found.',
            ]);
        }

        $request->session()->put('selected_device_id', $device_id);

        $tankStates = $device->tankStates()->get();
        $tankConfigs = $device->tankConfigs()->get();

        if ($tankStates->isEmpty()) {
            return Inertia::render('Dashboard', [
                'devices' => $devices,
                'tankStates' => [],
                'selectedDeviceId' => $device_id,
                'error' => 'No tank states found for this device.',
            ]);
        }

        return Inertia::render('Dashboard', ['devices' => $devices, "tankStates"=> $tankStates, "tankConfigs"=> $tankConfigs, "selectedDeviceId" => $device_id]);
    }
}
