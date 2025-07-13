<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'معرف الملف غير صالح']);
    exit;
}

$fileId = intval($_GET['id']);

try {
    $stmt = $pdo->prepare("SELECT * FROM uploads WHERE id = ?");
    $stmt->execute([$fileId]);
    $file = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$file) {
        echo json_encode(['success' => false, 'message' => 'الملف غير موجود']);
        exit;
    }
    
    echo json_encode(['success' => true, 'data' => $file]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'فشل في جلب تفاصيل الملف: ' . $e->getMessage()]);
}
?>