<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

try {
    // إحصائيات سريعة
    $quickStats = [
        'total_users' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
        'total_products' => $pdo->query("SELECT COUNT(*) FROM digital_products")->fetchColumn(),
        'total_orders' => $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
        'total_revenue' => (float)$pdo->query("SELECT IFNULL(SUM(amount), 0) FROM orders WHERE status = 'completed'")->fetchColumn()
    ];
    
    // بيانات المخطط الخطي (نمو المستخدمين)
    $userGrowth = $pdo->query("
        SELECT 
            DATE_FORMAT(created_at, '%Y-%m') AS month,
            COUNT(*) AS count
        FROM users
        WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY month
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    // بيانات مخطط الأعمدة (المنتجات الأكثر مبيعاً)
    $topProducts = $pdo->query("
        SELECT 
            p.product_name AS name,
            COUNT(o.id) AS sales
        FROM orders o
        JOIN digital_products p ON o.product_id = p.id
        WHERE o.status = 'completed'
        GROUP BY p.product_name
        ORDER BY sales DESC
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    // بيانات مخطط الدونات (حالة الطلبات)
    $orderStatus = $pdo->query("
        SELECT 
            status,
            COUNT(*) AS count
        FROM orders
        GROUP BY status
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    // بيانات المخطط القطبي (طرق الدفع)
    $paymentMethods = $pdo->query("
        SELECT 
            payment_method,
            COUNT(*) AS count
        FROM orders
        GROUP BY payment_method
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    // بيانات مخطط المنطقة (الإيرادات الشهرية)
    $monthlyRevenue = $pdo->query("
        SELECT 
            DATE_FORMAT(created_at, '%Y-%m') AS month,
            SUM(amount) AS revenue
        FROM orders
        WHERE status = 'completed' AND created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY month
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    // بيانات الخريطة الحرارية (نشاط الطلبات حسب ساعات اليوم)
    $orderActivity = $pdo->query("
        SELECT 
            HOUR(created_at) AS hour,
            COUNT(*) AS count
        FROM orders
        GROUP BY HOUR(created_at)
        ORDER BY hour
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    // إعداد البيانات للإرسال كـ JSON
    $response = [
        'quickStats' => $quickStats,
        'userGrowth' => $userGrowth,
        'topProducts' => $topProducts,
        'orderStatus' => $orderStatus,
        'paymentMethods' => $paymentMethods,
        'monthlyRevenue' => $monthlyRevenue,
        'orderActivity' => $orderActivity
    ];
    
    echo json_encode($response);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}