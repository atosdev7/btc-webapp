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

        $devices = Auth::user()->devices()->get();
        if ($devices->isEmpty()) {
            return Inertia::render('Dashboard', [
                'devices' => [],
                'tankStates' => [],
                'selectedDeviceId' => null,
                'error' => 'No devices found.',
            ]);
        }

        if ($device_id == null) {
            $device_id = $devices->first()->id; // Default to the first device if no ID is provided
            return redirect()->route('dashboard', ['device_id' => $device_id]);
        }

        if ($device_id) {
            $device = $devices->find($device_id);
            if (!$device) {
                return Inertia::render('Dashboard', [
                    'devices' => $devices,
                    'tankStates' => [],
                    'selectedDeviceId' => null,
                    'error' => 'Device is not selected',
                ]);
            }

            $tankStates = $device->tankStates()->get();
            $tankConfigs = $device->tankConfigs()->get();
            if ($tankStates->isEmpty()) {
                return Inertia::render('Dashboard', [
                    'devices' => $devices,
                    'tankStates' => [],
                    'selectedDeviceId' => $device_id,
                    'error' => 'No tank states found for this device.',
                ]);
            } else{
                return Inertia::render('Dashboard', ['devices' => $devices, "tankStates"=> $tankStates, "tankConfigs"=> $tankConfigs, "selectedDeviceId" => $device_id]);
            }
        } else {
            return Inertia::render('Dashboard', [
                'devices' => $devices,
                'tankStates' => [],
                'selectedDeviceId' => null,
                'error' => 'Device is not selected',
            ]);
        }
    }
}
