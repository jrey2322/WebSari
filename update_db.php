<?php
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/bootstrap.php';

$db = \Config\Database::connect();
$query = "ALTER TABLE activity_logs ADD COLUMN record_id INT(11) NULL AFTER details";

try {
    $db->query($query);
    echo "Successfully added record_id column to activity_logs table.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
