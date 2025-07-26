<?php
header('Content-Type: application/json');

// الاتصال بقاعدة البيانات
require_once '../includes/config.php';

$response = ['success' => false];

if (isset($_POST['id'])) {
    $personId = intval($_POST['id']);
    
    try {
        // جلب بيانات الشخص الأساسية
        $stmt = $pdo->prepare("SELECT * FROM persons WHERE id = ?");
        $stmt->execute([$personId]);
        $person = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($person) {
            $response['person'] = $person;
            
            // جلب المهارات
            $stmt = $pdo->prepare("SELECT * FROM person_skills WHERE person_id = ?");
            $stmt->execute([$personId]);
            $response['skills'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // جلب روابط التواصل
            $stmt = $pdo->prepare("SELECT * FROM person_social_links WHERE person_id = ?");
            $stmt->execute([$personId]);
            $response['socialLinks'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $response['success'] = true;
        } else {
            $response['message'] = 'لم يتم العثور على المستخدم';
        }
    } catch (PDOException $e) {
        $response['message'] = 'خطأ في قاعدة البيانات: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'معرف المستخدم غير موجود';
}

echo json_encode($response);
?>