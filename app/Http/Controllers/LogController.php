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

        if ($device_id == null) {
            $selectedDeviceId = $request->session()->get('selected_device_id', null);
            if ($selectedDeviceId) {
                return redirect()->route('logs.index', ['device_id' => $selectedDeviceId, 'tank_id' => $tank_id, 'date' => $date]);
            } else {
                try {
                    $device_id = Auth::user()->devices()->first()->id; // Default to the first device if no ID is provided
                    return redirect()->route('logs.index', ['device_id' => $device_id, 'tank_id' => $tank_id, 'date' => $date]);
                } catch (\Exception $e) {
                    return Inertia::render('Logs', [
                        'devices' => [],
                        'selectedDeviceId' => $device_id,
                        'selectedTankId' => $tank_id,
                        'selectedDate' => $date,
                        'logs'=> [],
                        'error' => 'No devices found.',
                    ]);
                }
            }
        }

        $devices = Auth::user()->devices()->get();
        $device = Auth::user()->devices()->find($device_id);

        if (!$device) {
            return Inertia::render('Logs', [
                'devices' => $devices,
                'selectedDeviceId' => $device_id,
                'selectedTankId' => null,
                'selectedDate' => null,
                'logs'=> [],
                'error' => 'Device is not selected',
            ]);
        }

        $request->session()->put('selected_device_id', $device_id);
        
        if ($tank_id == null || $date == null) {
            if ($tank_id == null) {
                $tank_id = $request->session()->get('selected_tank_id', 1);
            }
    
            if ($date == null) {
                $date = $request->session()->get('selected_date', null);
                if ($date) {
                    $date = Carbon::parse($date)->format('Y-m-d'); // Format the date to 'Y-m-d'
                } else {
                    $date = Carbon::now()->format('Y-m-d'); // Default to today's date if no date is provided
                }
            }
    
            return redirect()->route('logs.index', ['device_id' => $device_id, 'tank_id' => $tank_id, 'date' => $date]);
        }

        $request->session()->put('selected_tank_id', $tank_id);
        $request->session()->put('selected_date', $date);

        // Parse the date, ensuring it's well-formed
        $parsedDate = Carbon::parse($date);

        $logs = DB::table('logs')->select('id', 'time', 'current_temp', 'solenoid')
            ->where('device_id', $device_id)
            ->where('tank_id', $tank_id)
            ->whereDate('time', $parsedDate)
            ->orderBy('time', 'asc')
            ->get();
        
        return Inertia::render('Logs', ['devices' => $devices, "logs"=> $logs, "selectedDeviceId" => $device_id, "selectedTankId" => $tank_id, "selectedDate" => $date]);
    }
}
