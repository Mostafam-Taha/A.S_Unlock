<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'No ID provided']);
    exit;
}

$id = $_GET['id'];

try {
    // أولاً: جلب معلومات الصورة لحذفها من المجلد
    $stmt = $pdo->prepare("SELECT image_path FROM teams WHERE id = ?");
    $stmt->execute([$id]);
    $team = $stmt->fetch();
    
    if ($team) {
        // حذف الصورة من المجلد
        if (file_exists($team['image_path'])) {
            unlink($team['image_path']);
        }
        
        // حذف السجل من قاعدة البيانات
        $stmt = $pdo->prepare("DELETE FROM teams WHERE id = ?");
        $stmt->execute([$id]);
        
        echo json_encode(['success' => true, 'message' => 'تم الحذف بنجاح']);
    } else {
        echo json_encode(['success' => false, 'message' => 'لم يتم العثور على العضو']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}