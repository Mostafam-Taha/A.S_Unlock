<?php
header('Content-Type: application/json');

// الاتصال بقاعدة البيانات
require_once '../includes/config.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $personId = intval($_POST['editPersonId']);
    
    try {
        $pdo->beginTransaction();
        
        // تحديث بيانات الشخص الأساسية
        $updateData = [
            'type' => $_POST['editPersonType'],
            'name' => $_POST['editPersonName'],
            'job' => $_POST['editPersonJob'],
            'description' => $_POST['editPersonDescription'],
            'id' => $personId
        ];
        
        $sql = "UPDATE persons SET type = :type, name = :name, job = :job, description = :description WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($updateData);
        
        // تحديث الصورة إذا تم تحميلها
        if (!empty($_FILES['editUserImage']['name'])) {
            $targetDir = "uploads/";
            $fileName = basename($_FILES["editUserImage"]["name"]);
            $targetFile = $targetDir . uniqid() . '_' . $fileName;
            
            if (move_uploaded_file($_FILES["editUserImage"]["tmp_name"], $targetFile)) {
                $stmt = $pdo->prepare("UPDATE persons SET image_path = ? WHERE id = ?");
                $stmt->execute([$targetFile, $personId]);
            }
        }
        
        // حذف المهارات القديمة وإضافة الجديدة
        $stmt = $pdo->prepare("DELETE FROM person_skills WHERE person_id = ?");
        $stmt->execute([$personId]);
        
        if (!empty($_POST['editSkills'])) {
            $skillsStmt = $pdo->prepare("INSERT INTO person_skills (person_id, skill) VALUES (?, ?)");
            foreach ($_POST['editSkills'] as $skill) {
                if (!empty(trim($skill))) {
                    $skillsStmt->execute([$personId, trim($skill)]);
                }
            }
        }
        
        // حذف روابط التواصل القديمة وإضافة الجديدة
        $stmt = $pdo->prepare("DELETE FROM person_social_links WHERE person_id = ?");
        $stmt->execute([$personId]);
        
        if (!empty($_POST['editSocialIcons']) && !empty($_POST['editSocialLinks'])) {
            $socialStmt = $pdo->prepare("INSERT INTO person_social_links (person_id, icon_class, link) VALUES (?, ?, ?)");
            
            for ($i = 0; $i < count($_POST['editSocialIcons']); $i++) {
                $icon = trim($_POST['editSocialIcons'][$i]);
                $link = trim($_POST['editSocialLinks'][$i]);
                
                if (!empty($icon) && !empty($link)) {
                    $socialStmt->execute([$personId, $icon, $link]);
                }
            }
        }
        
        $pdo->commit();
        $response['success'] = true;
        $response['message'] = 'تم تحديث البيانات بنجاح';
    } catch (PDOException $e) {
        $pdo->rollBack();
        $response['message'] = 'خطأ في قاعدة البيانات: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'طريقة الطلب غير صحيحة';
}

echo json_encode($response);
?>