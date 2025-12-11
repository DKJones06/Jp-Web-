<?php
require_once __DIR__ . '/config.php';

$DB_HOST = "localhost";
$DB_NAME = "jp_notes";
$DB_USER = "root";
$DB_PASS = "";

try {
    $db = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
        $DB_USER,
        $DB_PASS
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (Exception $e) {
    die("Database forbindelse fejlede: " . $e->getMessage());
}
