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
    ->where('departments.name','Departemen Event Operations Logistics & Hospitality')
    ->select('users.id','users.name','users.email','users.department_id')
    ->get();

foreach ($rows as $r) {
    echo $r->id . '|' . $r->name . '|' . $r->email . '|' . ($r->department_id ?? 'NULL') . PHP_EOL;
}
