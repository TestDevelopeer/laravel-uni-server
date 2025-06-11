<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;

class SettingController extends Controller
{
    //
    public function index()
    {
        $settings = Setting::all();

        return view('settings.index', compact('settings'));
    }

    public function update(Setting $setting): RedirectResponse
    {
        $setting->status = !$setting->status;
        $setting->save();

        return redirect()->back();
    }
}
