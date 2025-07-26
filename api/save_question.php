<?php
require_once '../includes/config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'الطلب غير مسموح به'
    ]);
    exit;
}

// ===============================================================
// ==لو حصل مشكلة فعل ده عشان تعرف فين المشلكة تمام يا أحمد ==
// file_put_contents('debug.log', print_r($_POST, true), FILE_APPEND);
// ===============================================================

try {
    $required_fields = ['question', 'answer'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception('جميع الحقول مطلوبة');
        }
    }

    $question = trim($_POST['question']);
    $answer = trim($_POST['answer']);
    $is_published = isset($_POST['is_published']) ? 1 : 0;

    if (strlen($question) < 5 || strlen($answer) < 5) {
        throw new Exception('النص قصير جداً');
    }

    $stmt = $pdo->prepare("INSERT INTO questions (question, answer, is_published) VALUES (:question, :answer, :is_published)");
    
    $stmt->execute([
        ':question' => $question,
        ':answer' => $answer,
        ':is_published' => $is_published
    ]);

    $last_id = $pdo->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => 'تم حفظ السؤال بنجاح',
        'id' => $last_id
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage(),
        'error_code' => $e->getCode()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}