<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

try {
    // التحقق من أن الطلب POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('الطلب غير مسموح به');
    }

    // الحصول على البيانات من POST
    $icon = $_POST['icon'] ?? '';
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $discount = $_POST['discount'] ?? 0;
    $best_seller = $_POST['best_seller'] ?? 0;
    $features = $_POST['features'] ?? [];

    // التحقق من البيانات المطلوبة
    if (empty($name)) {
        throw new Exception('اسم الخطة مطلوب');
    }

    if (!is_numeric($price) || $price <= 0) {
        throw new Exception('سعر الخطة غير صالح');
    }

    // بدء transaction
    $pdo->beginTransaction();

    // إدخال بيانات الخطة الأساسية
    $stmt = $pdo->prepare("INSERT INTO plans (icon, name, price, discount, best_seller) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$icon, $name, $price, $discount, $best_seller]);
    $plan_id = $pdo->lastInsertId();

    // إدخال المميزات
    if (!empty($features)) {
        $featureStmt = $pdo->prepare("INSERT INTO plan_features (plan_id, feature) VALUES (?, ?)");
        foreach ($features as $feature) {
            if (!empty(trim($feature))) {
                $featureStmt->execute([$plan_id, trim($feature)]);
            }
        }
    }

    // تأكيد transaction
    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'تم حفظ الخطة بنجاح']);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}