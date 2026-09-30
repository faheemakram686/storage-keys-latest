<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\DB;
$dups = DB::table('profiles')->select('employee_id', DB::raw('count(*) as c'))->whereNotNull('employee_id')->where('employee_id','!=','')->groupBy('employee_id')->having('c','>',1)->get();
echo 'dup employee_ids='.count($dups).PHP_EOL;
foreach ($dups as $d) echo $d->employee_id.' x'.$d->c.PHP_EOL;
$edups = DB::table('users')->select('email', DB::raw('count(*) as c'))->groupBy('email')->having('c','>',1)->get();
echo 'dup emails='.count($edups).PHP_EOL;

