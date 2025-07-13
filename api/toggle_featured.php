<?php
// review-costm.php
require_once '../includes/config.php';

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'معرف العنصر مطلوب']);
    exit;
}

$itemId = $_GET['id'];

try {
    // احصل على الحالة الحالية
    $stmt = $pdo->prepare("SELECT is_featured FROM items WHERE id = ?");
    $stmt->execute([$itemId]);
    $current = $stmt->fetchColumn();

    // عكس الحالة
    $newStatus = $current ? 0 : 1;
    
    $stmt = $pdo->prepare("UPDATE items SET is_featured = ? WHERE id = ?");
    $stmt->execute([$newStatus, $itemId]);

    echo json_encode(['success' => true, 'is_featured' => $newStatus]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()]);
}