<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT * FROM links ORDER BY created_at DESC");
    $links = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $links
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}