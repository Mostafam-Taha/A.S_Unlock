<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: https://asunlock.ct.ws");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 3600");

// للطلبات من نوع OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// الاتصال بقاعدة البيانات
require_once '../includes/config.php';

// السماح بجميع أنواع الطلبات (GET, POST, DELETE, etc.)
$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'POST' && isset($_SERVER['HTTP_X_HTTP_METHOD'])) {
    if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
        $method = 'DELETE';
    } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
        $method = 'PUT';
    }
}

// الحصول على معرف المنتج من query parameters أو من body للطلبات POST
if ($method == 'DELETE') {
    $productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
} else {
    // للطلبات POST (في حالة استخدام fetch مع body)
    $input = json_decode(file_get_contents('php://input'), true);
    $productId = isset($input['id']) ? intval($input['id']) : 0;
}

if ($productId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit;
}

try {
    // التحقق من وجود المنتج أولاً
    $checkStmt = $pdo->prepare("SELECT id FROM digital_products WHERE id = ?");
    $checkStmt->execute([$productId]);
    
    if ($checkStmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        exit;
    }

    // بداية transaction
    $pdo->beginTransaction();

    // 1. حذف المرفقات أو السجلات المرتبطة أولاً (إذا وجدت)
    // $deleteAttachments = $pdo->prepare("DELETE FROM product_attachments WHERE product_id = ?");
    // $deleteAttachments->execute([$productId]);

    // 2. حذف المنتج الرئيسي
    $deleteStmt = $pdo->prepare("DELETE FROM digital_products WHERE id = ?");
    $deleteStmt->execute([$productId]);

    // تأكيد العملية
    $pdo->commit();
    
    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Product deleted successfully']);
} catch (PDOException $e) {
    // التراجع عن التغييرات في حالة حدوث خطأ
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>