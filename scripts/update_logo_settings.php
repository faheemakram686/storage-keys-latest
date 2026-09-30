<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

$skLogo = '/sk-assets/assets/images/frontend/front-logo.png';
$skIcon = '/sk-assets/assets/images/frontend/favicon.png';

$updated = DB::table('settings')
    ->whereIn('name', ['tenant_icon', 'app_icon', 'tenant_logo', 'app_logo'])
    ->where(function ($q) {
        $q->whereNull('value')
            ->orWhere('value', '')
            ->orWhere('value', 'like', '%/images/logo.png%')
            ->orWhere('value', 'like', '%/images/icon.png%')
            ->orWhere('value', 'like', '%/images/core.png%')
            ->orWhere('value', 'like', '%default-logo.png%');
    })
    ->get();

foreach ($updated as $row) {
    $value = in_array($row->name, ['tenant_icon', 'app_icon'], true) ? $skIcon : $skLogo;
    DB::table('settings')->where('id', $row->id)->update(['value' => $value]);
    echo "updated {$row->name} => {$value}\n";
}

// Ensure tenant logo setting exists
$hasLogo = DB::table('settings')->where('name', 'tenant_logo')->where('context', 'tenant')->exists();
if (!$hasLogo) {
    DB::table('settings')->insert([
        'name' => 'tenant_logo',
        'value' => $skLogo,
        'context' => 'tenant',
        'autoload' => 0,
        'public' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "inserted tenant_logo => {$skLogo}\n";
}

Artisan::call('cache:clear');
Artisan::call('config:clear');
Artisan::call('view:clear');
echo "cache cleared\n";
