<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$rows = DB::table('users')
    ->leftJoin('roles','users.role_id','=','roles.id')
    ->leftJoin('departments','users.department_id','=','departments.id')
    ->where('roles.name','kepala_divisi')
    ->select('users.id','users.name','users.email','departments.name as dept')
    ->orderBy('dept')
    ->get();

foreach ($rows as $r) {
    echo $r->id . '|' . $r->name . '|' . $r->email . '|' . ($r->dept ?? 'NULL') . PHP_EOL;
}
