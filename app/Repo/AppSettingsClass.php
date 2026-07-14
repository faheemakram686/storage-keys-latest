<?php
namespace App\Repo;

use App\Models\AppSettings;
use App\Models\Country;
use App\Repo\Interfaces\AppSettingsInterface;
use Illuminate\Http\Request;

class AppSettingsClass implements AppSettingsInterface {


    public function update($request)
    {
        $data = $request->except('_token');
        foreach ($data as $key => $value) {
            AppSettings::where('key', $key)->update(['value' => $value]);
        }
        return response()->json(['success' => 'App Settings updated successfully'], 200);
    }

    public function getAppSettings()
    {
        return AppSettings::query()
            ->select('id', 'key', 'value')
            ->orderBy('id')
            ->get();
    }
}
