<?php
require_once '../includes/config.php';

function createWarrantyForOrder($orderId) {
    global $pdo;
    
    try {
        // احصل على مدة الضمان من المنتج المرتبط بالطلب
        $stmt = $pdo->prepare("
            SELECT dp.warranty_duration_days 
            FROM orders o
            JOIN digital_products dp ON o.product_id = dp.id
            WHERE o.id = :order_id
        ");
        $stmt->execute([':order_id' => $orderId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$product || !$product['warranty_duration_days']) {
            return false; // لا يوجد ضمان لهذا المنتج
        }
        
        $startDate = date('Y-m-d H:i:s');
        $endDate = date('Y-m-d H:i:s', strtotime("+{$product['warranty_duration_days']} days"));
        
        // أدخل سجل الضمان الجديد
        $stmt = $pdo->prepare("
            INSERT INTO warranties (order_id, start_date, end_date, is_active)
            VALUES (:order_id, :start_date, :end_date, 0)
            ON DUPLICATE KEY UPDATE
                start_date = VALUES(start_date),
                end_date = VALUES(end_date)
        ");
        
        $stmt->execute([
            ':order_id' => $orderId,
            ':start_date' => $startDate,
            ':end_date' => $endDate
        ]);
        
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Error creating warranty: " . $e->getMessage());
        return false;
    }
}

// مثال لاستخدام الوظيفة عند الموافقة على الطلب
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_order'])) {
    $orderId = $_POST['order_id'];
    
    // أولاً، قم بتحديث حالة الطلب إلى "مكتمل" أو "موافق عليه"
    $stmt = $pdo->prepare("UPDATE orders SET status = 'completed' WHERE id = ?");
    $stmt->execute([$orderId]);
    
    // ثم أنشئ سجل الضمان
    $warrantyId = createWarrantyForOrder($orderId);
    
    if ($warrantyId) {
        echo json_encode(['success' => true, 'message' => 'تمت الموافقة على الطلب وإنشاء الضمان']);
    } else {
        echo json_encode(['success' => false, 'message' => 'تمت الموافقة على الطلب ولكن لم يتم إنشاء ضمان']);
    }
    exit;
}
?>