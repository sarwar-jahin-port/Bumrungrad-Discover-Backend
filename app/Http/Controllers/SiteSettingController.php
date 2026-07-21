<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSiteSettingsRequest;
use App\Models\SiteSetting;

class SiteSettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::pluck('value', 'key');
        return response()->json(['status' => 200, 'data' => $settings]);
    }

    public function update(UpdateSiteSettingsRequest $request)
    {
        foreach ($request->validated() as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        return response()->json(['status' => 200, 'msg' => 'Site settings updated.']);
    }
}
