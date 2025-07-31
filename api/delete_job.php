<?php
header('Content-Type: application/json');

// تضمين ملف الإعدادات
require_once '../includes/config.php';

// التحقق من أن الطلب POST وأن المعرف مرسل
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    try {
        // التحقق من أن المعرف رقم صحيح
        $jobId = filter_var($_POST['id'], FILTER_VALIDATE_INT);
        
        if ($jobId === false) {
            throw new Exception('معرف الوظيفة غير صحيح');
        }
        
        // استعداد الاستعلام لحذف الوظيفة
        $stmt = $pdo->prepare("DELETE FROM jobs WHERE id = ?");
        $stmt->execute([$jobId]);
        
        // التحقق من عدد الصفوف المتأثرة
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'تم حذف الوظيفة بنجاح']);
        } else {
            echo json_encode(['success' => false, 'message' => 'لم يتم العثور على الوظيفة أو حدث خطأ في الحذف']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'حدث خطأ: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'طلب غير صالح']);
}
?>