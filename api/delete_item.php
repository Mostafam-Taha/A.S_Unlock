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
    // احصل على مسارات الملفات أولاً لحذفها
    $stmt = $pdo->prepare("SELECT image_path, audio_path FROM items WHERE id = ?");
    $stmt->execute([$itemId]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    // حذف العنصر من قاعدة البيانات
    $stmt = $pdo->prepare("DELETE FROM items WHERE id = ?");
    $stmt->execute([$itemId]);

    // حذف الملفات المرتبطة إذا وجدت
    if ($item) {
        if ($item['image_path'] && file_exists($item['image_path'])) {
            unlink($item['image_path']);
        }
        if ($item['audio_path'] && file_exists($item['audio_path'])) {
            unlink($item['audio_path']);
        }
    }

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()]);
}