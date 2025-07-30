<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

// التحقق من أن الطريقة POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'طريقة الطلب غير مسموحة']);
    exit;
}

// التحقق من الحقول المطلوبة
$required = ['jobTitle', 'jobCategory', 'jobDescription'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => 'الحقول المطلوبة غير مكتملة']);
        exit;
    }
}

try {
    // تجهيز البيانات الأساسية
    $jobData = [
        'title' => $_POST['jobTitle'],
        'category' => $_POST['jobCategory'],
        'description' => $_POST['jobDescription'],
        'is_published' => isset($_POST['jobPublished']) ? 1 : 0,
        'show_popup' => isset($_POST['showPopup']) ? 1 : 0, // إضافة حقل show_popup
        'created_at' => date('Y-m-d H:i:s')
    ];

    // الحقول الاختيارية
    $optionalFields = [
        'jobType' => 'job_type',
        'jobSalary' => 'salary',
        'jobLocation' => 'location',
        'jobCompany' => 'company',
        'jobCompanyWebsite' => 'company_website',
        'jobExperience' => 'experience',
        'jobSkills' => 'skills',
        'jobBenefits' => 'benefits',
        'jobContactEmail' => 'contact_email',
        'jobDeadline' => 'deadline'
    ];

    foreach ($optionalFields as $postField => $dbField) {
        if (!empty($_POST[$postField])) {
            $jobData[$dbField] = $_POST[$postField];
        }
    }

    // معالجة رفع الصورة (مبسطة)
    if (!empty($_FILES['jobImage']['name'])) {
        $uploadDir = '../uploads/jobs/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileName = uniqid() . '_' . basename($_FILES['jobImage']['name']);
        $targetPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['jobImage']['tmp_name'], $targetPath)) {
            $jobData['image_path'] = $fileName;
        }
    }

    // إدراج البيانات في قاعدة البيانات
    $columns = implode(', ', array_keys($jobData));
    $placeholders = ':' . implode(', :', array_keys($jobData));
    
    $sql = "INSERT INTO jobs ($columns) VALUES ($placeholders)";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute($jobData)) {
        echo json_encode([
            'success' => true,
            'message' => 'تم إضافة الوظيفة بنجاح',
            'job_id' => $pdo->lastInsertId()
        ]);
    } else {
        throw new Exception('فشل في إضافة الوظيفة');
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'حدث خطأ: ' . $e->getMessage()
    ]);
}
?>