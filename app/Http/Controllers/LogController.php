<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Log;
use App\Models\Device;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $device_id = $request->query('device_id');
        $tank_id = $request->query('tank_id');
        $date = $request->query('date');

        $devices = Auth::user()->devices()->get();
        if ($devices->isEmpty()) {
            return Inertia::render('Logs', [
                'devices' => [],
                'selectedDeviceId' => $device_id,
                'selectedTankId' => $tank_id,
                'selectedDate' => $date,
                'logs'=> [],
                'error' => 'No devices found.',
            ]);
        }

        if ($device_id == null || $tank_id == null || $date == null) {
            if ($device_id == null) {
                $device_id = $devices->first()->id; // Default to the first device if no ID is provided
            }
            if ($tank_id == null) {
                $tank_id = 1; // Default to the first tank if no ID is provided
            }
            if ($date == null) {
                $date = Carbon::now()->format('Y-m-d'); // Default to today's date if no date is provided
            }
    
            return redirect()->route('logs.index', ['device_id' => $device_id, 'tank_id' => $tank_id, 'date' => $date]);
        }

        $device = $devices->find($device_id);
        if (!$device) {
            return Inertia::render('Logs', [
                'devices' => $devices,
                'selectedDeviceId' => $device_id,
                'selectedTankId' => $tank_id,
                'selectedDate' => $date,
                'logs'=> [],
                'error' => 'Device is not selected',
            ]);
        }

        // Parse the date, ensuring it's well-formed
        $parsedDate = Carbon::parse($date);

        $logs = DB::table('logs')->select('id', 'time', 'current_temp', 'solenoid')
            ->where('device_id', $device_id)
            ->where('tank_id', $tank_id)
            ->whereDate('time', $parsedDate)
            ->get();
        
        return Inertia::render('Logs', ['devices' => $devices, "logs"=> $logs, "selectedDeviceId" => $device_id, "selectedTankId" => $tank_id, "selectedDate" => $date]);
    }
}
