<?php
header('Content-Type: application/json');

// الاتصال بقاعدة البيانات
require_once '../includes/config.php';

// التحقق من أن الطلب هو DELETE
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// الحصول على معرف المنتج من query parameters
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($productId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit;
}

try {
    // التحقق من وجود المنتج أولاً
    $checkStmt = $pdo->prepare("SELECT id FROM digital_products WHERE id = ?");
    $checkStmt->execute([$productId]);
    
    if ($checkStmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }

    // حذف المنتج
    $deleteStmt = $pdo->prepare("DELETE FROM digital_products WHERE id = ?");
    $deleteStmt->execute([$productId]);

    
    echo json_encode(['success' => true, 'message' => 'Product deleted successfully']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>