<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Department;

$eventDeptName = 'Departemen Event Operations Logistics & Hospitality';
$eventDept = Department::where('name', $eventDeptName)->first();
if (! $eventDept) {
    echo "Event department not found: $eventDeptName\n";
    exit(1);
}

// Assign Ferdi Almansah to event dept
$ferdi = User::where('name', 'Ferdi Almansah')->first();
if ($ferdi) {
    echo "Found Ferdi (id={$ferdi->id}), current department_id={$ferdi->department_id}\n";
    $ferdi->department_id = $eventDept->id;
    $ferdi->save();
    echo "Updated Ferdi department_id to {$eventDept->id}\n";
} else {
    echo "Ferdi Almansah not found\n";
}

// Remove generic kepala for this department
$generic = User::where('name', 'like', 'Ka. Departemen%')
    ->where('department_id', $eventDept->id)
    ->get();
if ($generic->isEmpty()) {
    echo "No generic kepala found for event dept\n";
} else {
    foreach ($generic as $g) {
        echo "Deleting generic kepala id={$g->id} name={$g->name} email={$g->email}\n";
        $g->delete();
    }
}

echo "Done.\n";
