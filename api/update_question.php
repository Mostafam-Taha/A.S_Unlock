<?php
require_once '../includes/config.php';

try {
    $question_id = $_POST['question_id'];
    $question = $_POST['question'];
    $answer = $_POST['answer'];
    $is_published = isset($_POST['is_published']) ? 1 : 0;

    $stmt = $pdo->prepare("UPDATE questions SET question = ?, answer = ?, is_published = ? WHERE id = ?");
    $stmt->execute([$question, $answer, $is_published, $question_id]);

    // إعادة التوجيه إلى صفحة أخرى بعد التحديث
    header("Location: ../admin/common-questions.php?success=1");
    exit();
} catch (PDOException $e) {
    // في حالة الخطأ فقط نعرض رسالة الخطأ
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}