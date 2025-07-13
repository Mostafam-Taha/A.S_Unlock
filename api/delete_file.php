<?php
header('Content-Type: application/json');
require_once '../includes/config.php';

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('طريقة الطلب غير مسموحة');
    }

    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        $data = $_POST;
    }

    if (!isset($data['id']) || empty($data['id'])) {
        throw new Exception('معرف الملف مطلوب');
    }

    $id = (int)$data['id'];
    $path = isset($data['path']) ? $data['path'] : null;
    
    // التحقق مما إذا كان الملف موجودًا
    $stmt = $pdo->prepare("SELECT * FROM uploads WHERE id = ?");
    $stmt->execute([$id]);
    $file = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$file) {
        throw new Exception('الملف غير موجود');
    }
    
    // بدء معاملة
    $pdo->beginTransaction();
    
    try {
        // حذف الملف من قاعدة البيانات
        $stmt = $pdo->prepare("DELETE FROM uploads WHERE id = ?");
        $stmt->execute([$id]);
        
        // إذا كان ملفًا وليس رابطًا، حذف الملف من النظام
        if ($file['link_url'] === null && $path && file_exists($path)) {
            if (!unlink($path)) {
                throw new Exception('فشل في حذف الملف من النظام');
            }
        }
        
        $pdo->commit();
        $response['success'] = true;
        $response['message'] = 'تم الحذف بنجاح';
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>