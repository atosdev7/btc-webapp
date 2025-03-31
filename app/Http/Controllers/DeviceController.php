<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Device;
use Inertia\Inertia;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = Auth::user()->devices()->get();

        return Inertia::render('Devices', ['devices' => $devices]);
    }

    public function create()
    {
        return Inertia::render('Devices/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'board_id' => [
                'required',
                'string',
                'regex:/^BTC-\d+$/', // Validate the format BTC-<integer>
            ],
        ], [
            'board_id.regex' => 'The board ID must be in the format BTC-<integer> (e.g., BTC-00001).',
        ]);

        // Extract the integer part from the board_id
        $boardId = (int) str_replace('BTC-', '', $request->board_id);

        // Create the device
        $device = Device::create([
            'name' => $request->name,
            'board_id' => $boardId,
            'tank_count' => $request->tank_count,
        ]);

        // Assign the device to the authenticated user
        $user = Auth::user();
        $user->devices()->attach($device->id);

        return redirect()->route('devices.index')->with('success', 'Device created.');
    }

    public function edit(Device $device)
    {
        return Inertia::render('Devices/Edit', ['device' => $device]);
    }

    public function update(Request $request, Device $device)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'board_id' => [
                'required',
                'string',
                'regex:/^BTC-\d+$/', // Validate the format BTC-<integer>
            ],
            'tank_count' => 'required|integer|min:1',
        ], [
            'board_id.regex' => 'The board ID must be in the format BTC-<integer> (e.g., BTC-00001).',
        ]);
    
        // Extract the integer part from the board_id
        $boardId = (int) str_replace('BTC-', '', $request->board_id);
    
        // Update the device with the extracted integer value for board_id
        $device->update([
            'name' => $request->name,
            'board_id' => $boardId,
            'tank_count' => $request->tank_count,
        ]);

        return redirect()->route('devices.index')->with('success', 'Device updated.');
    }

    public function destroy(Device $device)
    {
        // Detach the device from the authenticated user
        $user = Auth::user();
        $user->devices()->detach($device->id);
                
        $device->delete();
        return redirect()->route('devices.index')->with('success', 'Device deleted.');
    }
}
