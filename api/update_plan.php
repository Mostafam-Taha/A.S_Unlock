<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

$data = $_POST;

try {
    // بدء transaction
    $pdo->beginTransaction();
    
    // تحديث بيانات الخطة الأساسية
    $stmt = $pdo->prepare("UPDATE plans SET 
        name = :name, 
        price = :price, 
        discount = :discount, 
        best_seller = :best_seller, 
        status = :status, 
        updated_at = NOW() 
        WHERE id = :id");
    
    $stmt->execute([
        ':name' => $data['name'],
        ':price' => $data['price'],
        ':discount' => $data['discount'] ?? 0,
        ':best_seller' => isset($data['best_seller']) ? 1 : 0,
        ':status' => isset($data['status']) ? 1 : 0,
        ':id' => $data['id']
    ]);
    
    // حذف المميزات القديمة
    $stmt = $pdo->prepare("DELETE FROM plan_features WHERE plan_id = ?");
    $stmt->execute([$data['id']]);
    
    // إضافة المميزات الجديدة
    if (!empty($data['features'])) {
        $features = explode("\n", $data['features']);
        $stmt = $pdo->prepare("INSERT INTO plan_features (plan_id, feature) VALUES (?, ?)");
        
        foreach ($features as $feature) {
            $feature = trim($feature);
            if (!empty($feature)) {
                $stmt->execute([$data['id'], $feature]);
            }
        }
    }
    
    $pdo->commit();
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()]);
}