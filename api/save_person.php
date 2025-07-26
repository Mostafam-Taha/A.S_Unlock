<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../includes/config.php';

// تعيين رأس JSON مسبقاً
header('Content-Type: application/json');

// دالة لمعالجة الأخطاء
function handleError($message, $code = 400) {
    http_response_code($code);
    die(json_encode(['success' => false, 'message' => $message]));
}

try {
    // التحقق من طريقة الطلب
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        handleError('الطلب يجب أن يكون من نوع POST', 405);
    }

    // التحقق من وجود البيانات الأساسية
    $requiredFields = ['personType', 'personName', 'personJob'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            handleError('حقل ' . $field . ' مطلوب');
        }
    }

    // جمع البيانات
    $data = [
        'type' => $_POST['personType'],
        'name' => $_POST['personName'],
        'job' => $_POST['personJob'],
        'description' => $_POST['personDescription'] ?? null,
        'skills' => array_filter($_POST['skills'] ?? [], function($skill) {
            return !empty(trim($skill));
        }),
        'socialIcons' => $_POST['socialIcons'] ?? [],
        'socialLinks' => $_POST['socialLinks'] ?? []
    ];

    // معالجة الصورة
    $imagePath = null;
    if (isset($_FILES['userImage']) && $_FILES['userImage']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['userImage']['type'], $allowedTypes)) {
            handleError('نوع الملف غير مسموح به');
        }

        $uploadDir = '../uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo($_FILES['userImage']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['userImage']['tmp_name'], $destination)) {
            $imagePath = $filename;
        }
    }

    // بدء المعاملة مع قاعدة البيانات
    $pdo->beginTransaction();

    try {
        // إضافة الشخص
        $stmt = $pdo->prepare("INSERT INTO persons (type, name, job, description, image_path) 
                              VALUES (:type, :name, :job, :description, :image_path)");
        $stmt->execute([
            ':type' => $data['type'],
            ':name' => $data['name'],
            ':job' => $data['job'],
            ':description' => $data['description'],
            ':image_path' => $imagePath
        ]);
        $personId = $pdo->lastInsertId();

        // إضافة المهارات
        if (!empty($data['skills'])) {
            $skillStmt = $pdo->prepare("INSERT INTO person_skills (person_id, skill) VALUES (:person_id, :skill)");
            foreach ($data['skills'] as $skill) {
                $skillStmt->execute([':person_id' => $personId, ':skill' => $skill]);
            }
        }

        // إضافة روابط التواصل
        if (!empty($data['socialIcons']) && !empty($data['socialLinks'])) {
            $socialStmt = $pdo->prepare("INSERT INTO person_social_links (person_id, icon_class, link) 
                                        VALUES (:person_id, :icon_class, :link)");
            for ($i = 0; $i < count($data['socialIcons']); $i++) {
                if (!empty($data['socialIcons'][$i]) && !empty($data['socialLinks'][$i])) {
                    $socialStmt->execute([
                        ':person_id' => $personId,
                        ':icon_class' => $data['socialIcons'][$i],
                        ':link' => $data['socialLinks'][$i]
                    ]);
                }
            }
        }

        $pdo->commit();

        echo json_encode([
            'success' => true,
            'message' => 'تم حفظ البيانات بنجاح',
            'personId' => $personId
        ]);

    } catch (PDOException $e) {
        $pdo->rollBack();
        handleError('خطأ في قاعدة البيانات: ' . $e->getMessage(), 500);
    }

} catch (Exception $e) {
    handleError($e->getMessage());
}