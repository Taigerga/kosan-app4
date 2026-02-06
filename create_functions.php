<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$sql = file_get_contents(__DIR__ . '/functions.sql');

try {
    DB::unprepared($sql);
    echo "Stored functions created successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
