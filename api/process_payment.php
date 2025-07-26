<?php
session_start();
require_once '../includes/config.php';

// تمكين عرض الأخطاء للتصحيح
error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = ['success' => false, 'message' => ''];

try {
    if(!isset($_SESSION['user_id'])) {
        throw new Exception('يجب تسجيل الدخول أولاً');
    }

    // تسجيل البيانات الواردة للتصحيح
    file_put_contents('payment_log.txt', print_r($_POST, true) . print_r($_FILES, true), FILE_APPEND);

    // التحقق من البيانات المطلوبة
    $required = ['plan_id', 'payment_method', 'phone_number', 'email'];
    foreach($required as $field) {
        if(empty($_POST[$field])) {
            throw new Exception('حقل ' . $field . ' مطلوب');
        }
    }

    // معالجة رفع الملف
    if(!isset($_FILES['receipt_image']) || $_FILES['receipt_image']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('يجب رفع إيصال الدفع');
    }

    $uploadDir = '../uploads/receipts/';
    if(!is_dir($uploadDir)) {
        if(!mkdir($uploadDir, 0755, true)) {
            throw new Exception('لا يمكن إنشاء مجلد التحميل');
        }
    }

    $extension = pathinfo($_FILES['receipt_image']['name'], PATHINFO_EXTENSION);
    $filename = 'receipt_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
    $targetPath = $uploadDir . $filename;

    if(!move_uploaded_file($_FILES['receipt_image']['tmp_name'], $targetPath)) {
        throw new Exception('حدث خطأ أثناء رفع الملف');
    }

    // جلب سعر الخطة من قاعدة البيانات
    $stmt = $pdo->prepare("SELECT price FROM plans WHERE id = ?");
    $stmt->execute([$_POST['plan_id']]);
    $plan = $stmt->fetch();

    if(!$plan) {
        throw new Exception('الخطة غير موجودة');
    }

    // حفظ البيانات في جدول الطلبات
    // جلب بيانات الخطة من قاعدة البيانات
    $stmt = $pdo->prepare("SELECT name, price FROM plans WHERE id = ?");
    $stmt->execute([$_POST['plan_id']]);
    $plan = $stmt->fetch();

    if(!$plan) {
        throw new Exception('الخطة غير موجودة');
    }

    // حفظ البيانات في جدول الطلبات
    $stmt = $pdo->prepare("
        INSERT INTO orders (
            user_id, plan_id, plan_name, plan_price, payment_method, 
            phone_number, email, subscription_email, 
            receipt_image, amount, status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
    ");

    $success = $stmt->execute([
        $_SESSION['user_id'],
        $_POST['plan_id'],
        $plan['name'],  // اسم الخطة
        $plan['price'], // سعر الخطة
        $_POST['payment_method'],
        $_POST['phone_number'],
        $_POST['email'],
        $_POST['subscription_email'] ?? null,
        $filename,
        $plan['price'], // يمكنك استخدام نفس سعر الخطة أو قيمة أخرى
    ]);

    if(!$success) {
        throw new Exception('فشل في حفظ البيانات');
    }

    $response['success'] = true;
    $response['message'] = 'تم حفظ الطلب بنجاح';

} catch(PDOException $e) {
    $response['message'] = 'خطأ في قاعدة البيانات: ' . $e->getMessage();
} catch(Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>