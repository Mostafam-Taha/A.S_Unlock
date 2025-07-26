<?php
// تأكد من عدم وجود أي محتوى قبل أو بعد الـ JSON
ob_start(); // بدء buffer للمخرجات
require_once '../includes/config.php';

header('Content-Type: application/json');

try {
    if(!isset($_POST['id'])) {
        throw new Exception('معرف السؤال غير موجود');
    }

    $question_id = (int)$_POST['id'];
    
    // التحقق من وجود السؤال قبل الحذف
    $stmt = $pdo->prepare("SELECT id FROM questions WHERE id = ?");
    $stmt->execute([$question_id]);
    
    if(!$stmt->fetch()) {
        throw new Exception('السؤال غير موجود');
    }
    
    // حذف السؤال
    $stmt = $pdo->prepare("DELETE FROM questions WHERE id = ?");
    $stmt->execute([$question_id]);
    
    // تنظيف buffer وإرسال JSON
    ob_end_clean();
    die(json_encode(['success' => true]));
    
} catch (Exception $e) {
    ob_end_clean();
    die(json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]));
}