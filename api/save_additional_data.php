<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('طريقة الطلب غير مسموحة');
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    // تحديث جدول المنتجات الرئيسي
    $stmt = $pdo->prepare("
        UPDATE digital_products SET
            instructions = ?,
            is_special_offer = ?,
            updated_at = NOW()
        WHERE id = ?
    ");
    
    $stmt->execute([
        $data['instructions'],
        $data['isSpecialOffer'],
        $data['productId']
    ]);
    
    // حفظ بيانات الخطة كسجل جديد (إن وجدت)
    if (!empty($data['planType'])) {
        // أولاً: التحقق مما إذا كانت الخطة الحالية مختلفة عن الأخيرة المسجلة
        $stmt = $pdo->prepare("
            SELECT plan_type FROM product_plans 
            WHERE product_id = ? 
            ORDER BY created_at DESC 
            LIMIT 1
        ");
        $stmt->execute([$data['productId']]);
        $lastPlan = $stmt->fetch();
        
        // إذا كانت الخطة مختلفة أو لا توجد خطط سابقة، نضيف سجل جديد
        if (!$lastPlan || $lastPlan['plan_type'] != $data['planType']) {
            $stmt = $pdo->prepare("
                INSERT INTO product_plans (
                    product_id,
                    plan_type,
                    plan_name,
                    plan_price,
                    plan_features,
                    created_at
                ) VALUES (?, ?, ?, ?, ?, NOW())
            ");
            
            $stmt->execute([
                $data['productId'],
                $data['planType'],
                $data['planName'],
                $data['planPrice'],
                json_encode($data['planFeatures'])
            ]);
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'تم حفظ البيانات بنجاح'
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}