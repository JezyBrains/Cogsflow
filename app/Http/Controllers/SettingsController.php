<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display the system settings view.
     */
    public function index()
    {
        $settings = \App\Models\SystemSetting::all()->pluck('value', 'key');
        return view('settings.index', compact('settings'));
    }

    /**
     * Update system settings.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'key' => 'required|string',
            'value' => 'nullable',
            'type' => 'required|string'
        ]);

        \App\Models\SystemSetting::setVal($data['key'], $data['value'], $data['type']);

        return response()->json([
            'status' => 'success',
            'message' => 'Setting updated successfully'
        ]);
    }

    /**
     * Display company profile.
     */
    public function company()
    {
        return view('settings.company');
    }

    /**
     * Display notification rules.
     */
    public function notifications()
    {
        return view('settings.notifications');
    }

    /**
     * Display the System Health & Operational Logs
     */
    public function logs()
    {
        $logs = \App\Models\AuditTrail::with('user')->latest()->paginate(50);
        return view('settings.logs', compact('logs'));
    }
}

// SupportController in the same tool call if possible, but I'll do it separately for cleaner file management.
