<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'لم يتم تحديد معرف الخطة']);
    exit;
}

$planId = intval($_GET['id']);

try {
    // جلب بيانات الخطة
    $stmt = $pdo->prepare("SELECT * FROM plans WHERE id = ?");
    $stmt->execute([$planId]);
    $plan = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$plan) {
        echo json_encode(['success' => false, 'message' => 'الخطة غير موجودة']);
        exit;
    }
    
    // جلب مميزات الخطة
    $stmt = $pdo->prepare("SELECT feature FROM plan_features WHERE plan_id = ?");
    $stmt->execute([$planId]);
    $features = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'plan' => $plan,
        'features' => $features
    ]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()]);
}