<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    DB::unprepared('DROP TRIGGER IF EXISTS id_store');
    DB::unprepared("CREATE TRIGGER id_store BEFORE INSERT ON users FOR EACH ROW\nBEGIN\n    INSERT INTO sequence_tbls VALUES (NULL);\n    SET NEW.rec_id = CONCAT('KHM_', LPAD(LAST_INSERT_ID(), 10, '0'));\nEND");
    echo "Trigger created\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
