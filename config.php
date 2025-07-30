<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (ob_get_level()) ob_clean();

$host = 'sql211.infinityfree.com';
$dbname = 'if0_39457751_as_unlock';
$username = 'if0_39457751';
$password = 'FnNPGDmBPL';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    
    if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
        header('Content-Type: application/json');
        die(json_encode(['success' => false, 'message' => 'Could not connect to the database']));
    } else {
        die("Could not connect to the database: " . $e->getMessage());
    }
}