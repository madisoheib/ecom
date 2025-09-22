<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $themeSettings = Setting::getGroup('theme');

        // Set default values if not exist
        $settings = [
            'primary_color' => $themeSettings['primary_color'] ?? '#2e073b',
            'secondary_color' => $themeSettings['secondary_color'] ?? '#4a1a4a',
            'accent_color' => $themeSettings['accent_color'] ?? '#8b5cf6',
            'background_color' => $themeSettings['background_color'] ?? '#f8f9fa',
            'text_color' => $themeSettings['text_color'] ?? '#1f2937',
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function updateTheme(Request $request)
    {
        $request->validate([
            'primary_color' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'accent_color' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'background_color' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'text_color' => 'required|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        // Update settings
        Setting::set('primary_color', $request->primary_color, 'string', 'theme', 'Primary brand color');
        Setting::set('secondary_color', $request->secondary_color, 'string', 'theme', 'Secondary brand color');
        Setting::set('accent_color', $request->accent_color, 'string', 'theme', 'Accent color for highlights');
        Setting::set('background_color', $request->background_color, 'string', 'theme', 'Background color');
        Setting::set('text_color', $request->text_color, 'string', 'theme', 'Text color');

        return redirect()->back()->with('success', 'Theme colors updated successfully!');
    }
}
