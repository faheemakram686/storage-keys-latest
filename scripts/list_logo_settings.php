<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$rows = DB::table('settings')
    ->where(function ($q) {
        $q->where('name', 'like', '%logo%')
            ->orWhere('name', 'like', '%icon%');
    })
    ->get(['id', 'name', 'value', 'context']);

foreach ($rows as $r) {
    echo "{$r->id} | {$r->name} | {$r->context} | {$r->value}\n";
}
