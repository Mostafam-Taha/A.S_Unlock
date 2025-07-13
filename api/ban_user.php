<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

try {
    // التحقق من أن الطلب POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('طريقة الطلب غير مسموح بها');
    }

    // التحقق من وجود البيانات المطلوبة
    if (!isset($_POST['user_id'], $_POST['action'])) {
        throw new Exception('بيانات ناقصة');
    }

    $userId = (int)$_POST['user_id'];
    $action = $_POST['action'];

    // التحقق من أن الإجراء صحيح
    if (!in_array($action, ['ban', 'unban'])) {
        throw new Exception('إجراء غير صحيح');
    }

    // تحديث حالة الحظر في قاعدة البيانات
    $bannedValue = $action === 'ban' ? 1 : 0;
    $stmt = $pdo->prepare("UPDATE users SET banned = ? WHERE id = ?");
    $stmt->execute([$bannedValue, $userId]);

    // التحقق من أن التحديث نجح
    if ($stmt->rowCount() === 0) {
        throw new Exception('لم يتم العثور على المستخدم أو لم يتم تحديث البيانات');
    }

    // إرسال استجابة نجاح
    echo json_encode([
        'success' => true,
        'message' => $action === 'ban' ? 'تم حظر المستخدم بنجاح' : 'تم فك حظر المستخدم بنجاح'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}