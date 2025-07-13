<?php
// review-costm.php
require_once '../includes/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'طريقة الطلب غير صحيحة']);
    exit;
}

$id = $_POST['id'] ?? null;
$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
$isFeatured = isset($_POST['is_featured']) ? 1 : 0;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'معرف العنصر مطلوب']);
    exit;
}

try {
    // تحديث البيانات الأساسية
    $stmt = $pdo->prepare("UPDATE items SET name = ?, description = ?, is_featured = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$name, $description, $isFeatured, $id]);

    // معالجة رفع الصورة
    if (!empty($_FILES['image']['name'])) {
        $imageFile = $_FILES['image'];
        $imageExt = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
        $newImageName = 'item_' . $id . '_' . time() . '.webp';
        $imagePath = '../uploads/images/' . $newImageName;
        
        if (move_uploaded_file($imageFile['tmp_name'], $imagePath)) {
            $stmt = $pdo->prepare("UPDATE items SET image_path = ? WHERE id = ?");
            $stmt->execute([$imagePath, $id]);
        }
    }

    // معالجة رفع الصوت
    if (!empty($_FILES['audio']['name'])) {
        $audioFile = $_FILES['audio'];
        $audioExt = pathinfo($audioFile['name'], PATHINFO_EXTENSION);
        $newAudioName = 'item_' . $id . '_' . time() . '.' . $audioExt;
        $audioPath = '../uploads/audio/' . $newAudioName;
        
        if (move_uploaded_file($audioFile['tmp_name'], $audioPath)) {
            $stmt = $pdo->prepare("UPDATE items SET audio_path = ? WHERE id = ?");
            $stmt->execute([$audioPath, $id]);
        }
    }

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()]);
}