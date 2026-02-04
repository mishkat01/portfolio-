<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->all();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'user_registration' => 'nullable|in:on,off',
            'email_verification' => 'nullable|in:on,off',
        ]);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value === 'on' ? '1' : '0']);
        }
        
        // Handle unchecked checkboxes (they are not sent in request)
        $checkboxes = ['user_registration', 'email_verification'];
        foreach ($checkboxes as $key) {
           if (!$request->has($key)) {
                Setting::updateOrCreate(['key' => $key], ['value' => '0']);
           }
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
