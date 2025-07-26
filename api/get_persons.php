<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT * FROM persons ORDER BY created_at DESC");
    $persons = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'data' => $persons,
        'count' => count($persons)
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في جلب البيانات: ' . $e->getMessage(),
        'data' => [],
        'count' => 0
    ]);
}