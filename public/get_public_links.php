<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

try {
    // جلب فقط الروابط المنشورة (is_published = 1)
    $stmt = $pdo->query("SELECT * FROM links WHERE is_published = 1 ORDER BY created_at DESC");
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