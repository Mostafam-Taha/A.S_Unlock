<?php
require_once '../includes/config.php';
require_once '../includes/telegram_notifier.php';

header('Content-Type: application/json');

try {
    // التحقق من أن الطلب هو POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // التحقق من وجود البيانات المطلوبة
    $requiredFields = ['fullName', 'phoneNumber', 'address', 'workType', 'skills'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }

    // معالجة رفع الصورة
    $profileImagePath = null;
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $_FILES['profileImage']['tmp_name']);
        finfo_close($fileInfo);

        if (!in_array($mimeType, $allowedTypes)) {
            throw new Exception('Invalid file type. Only JPG and PNG are allowed.');
        }

        if ($_FILES['profileImage']['size'] > $maxSize) {
            throw new Exception('File size exceeds maximum limit of 5MB.');
        }

        // إنشاء مجلد التخزين إذا لم يكن موجوداً
        $uploadDir = '../uploads/profile_images/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // إنشاء اسم فريد للملف
        $extension = pathinfo($_FILES['profileImage']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('profile_') . '.' . $extension;
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $destination)) {
            $profileImagePath = $destination;
        } else {
            throw new Exception('Failed to upload profile image.');
        }
    }

    // تنظيف البيانات
    $fullName = htmlspecialchars(trim($_POST['fullName']));
    $phoneNumber = htmlspecialchars(trim($_POST['phoneNumber']));
    $address = htmlspecialchars(trim($_POST['address']));
    $workType = $_POST['workType'] === 'online' ? 'online' : 'offline';
    $skills = is_array($_POST['skills']) ? implode(', ', $_POST['skills']) : $_POST['skills'];

    // إدخال البيانات في قاعدة البيانات
    $stmt = $pdo->prepare("
        INSERT INTO job_applications 
        (full_name, phone_number, address, work_type, skills, profile_image_path) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $fullName,
        $phoneNumber,
        $address,
        $workType,
        $skills,
        $profileImagePath
    ]);

    // إرسال الإشعار إلى Telegram
    $telegramData = [
        'fullName' => $fullName,
        'phoneNumber' => $phoneNumber,
        'address' => $address,
        'workType' => $workType,
        'skills' => $skills
    ];
    
    sendToTelegram($telegramData);

    echo json_encode([
        'success' => true,
        'message' => 'تم تقديم طلبك بنجاح!',
        'applicationId' => $pdo->lastInsertId()
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'حدث خطأ: ' . $e->getMessage()
    ]);
}