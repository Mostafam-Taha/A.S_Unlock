<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

if (!isset($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'لم يتم تحديد معرف الخطة']);
    exit;
}

$planId = intval($_POST['id']);

try {
    // بدء transaction
    $pdo->beginTransaction();
    
    // حذف مميزات الخطة أولاً
    $stmt = $pdo->prepare("DELETE FROM plan_features WHERE plan_id = ?");
    $stmt->execute([$planId]);
    
    // ثم حذف الخطة نفسها
    $stmt = $pdo->prepare("DELETE FROM plans WHERE id = ?");
    $stmt->execute([$planId]);
    
    $pdo->commit();
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()]);
}