<?php
header('Content-Type: application/json');
require_once '../includes/config.php';

// التحقق من أن الطريقة POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['success' => false, 'message' => 'الطريقة غير مسموحة']));
}

// الحصول على البيانات المرسلة
$data = json_decode(file_get_contents('php://input'), true);

// التحقق من البيانات المطلوبة
if (empty($data['name']) || empty($data['url']) || !isset($data['size_mb'])) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'بيانات ناقصة']));
}

// تنظيف البيانات
$name = trim($data['name']);
$url = trim($data['url']);
$size_mb = floatval($data['size_mb']);
$description = isset($data['description']) ? trim($data['description']) : '';

// التحقق من صحة الرابط
if (!filter_var($url, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'رابط غير صحيح']));
}

// التحقق من أن حجم الملف رقم موجب
if ($size_mb <= 0) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'حجم الملف يجب أن يكون رقمًا موجبًا']));
}

// حفظ الرابط في قاعدة البيانات
try {
    $stmt = $pdo->prepare("
        INSERT INTO uploaded_links 
        (link_name, link_url, file_size_mb, description) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$name, $url, $size_mb, $description]);
    
    echo json_encode([
        'success' => true,
        'message' => 'تم حفظ الرابط بنجاح',
        'link_id' => $pdo->lastInsertId()
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'حدث خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>