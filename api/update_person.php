<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../includes/config.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['editPersonId'])) {
        $response['message'] = 'معرف الشخص غير موجود';
        echo json_encode($response);
        exit;
    }

    $personId = intval($_POST['editPersonId']);

    try {
        $pdo->beginTransaction();

        // تحديث بيانات الشخص
        $updateData = [
            'type' => $_POST['editPersonType'] ?? '',
            'name' => $_POST['editPersonName'] ?? '',
            'job' => $_POST['editPersonJob'] ?? '',
            'description' => $_POST['editPersonDescription'] ?? '',
            'id' => $personId
        ];

        $sql = "UPDATE persons SET type = :type, name = :name, job = :job, description = :description WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        if (!$stmt->execute($updateData)) {
            throw new PDOException('فشل تحديث بيانات الشخص');
        }

        // تحديث الصورة
        if (!empty($_FILES['editUserImage']['name'])) {
            $targetDir = "../uploads/";  // تأكد من المسار الصحيح
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $fileName = basename($_FILES["editUserImage"]["name"]);
            $targetFile = $targetDir . uniqid() . '_' . $fileName;

            if (move_uploaded_file($_FILES["editUserImage"]["tmp_name"], $targetFile)) {
                $stmt = $pdo->prepare("UPDATE persons SET image_path = ? WHERE id = ?");
                if (!$stmt->execute([$targetFile, $personId])) {
                    throw new PDOException('فشل تحديث الصورة');
                }
            } else {
                throw new PDOException('فشل تحميل الصورة');
            }
        }

        // تحديث المهارات
        $stmt = $pdo->prepare("DELETE FROM person_skills WHERE person_id = ?");
        $stmt->execute([$personId]);

        if (!empty($_POST['editSkills'])) {
            $skillsStmt = $pdo->prepare("INSERT INTO person_skills (person_id, skill) VALUES (?, ?)");
            foreach ($_POST['editSkills'] as $skill) {
                $skill = trim($skill);
                if (!empty($skill)) {
                    $skillsStmt->execute([$personId, $skill]);
                }
            }
        }

        // تحديث روابط التواصل
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
        $response['message'] = 'تم التحديث بنجاح';
    } catch (PDOException $e) {
        $pdo->rollBack();
        $response['message'] = 'خطأ في قاعدة البيانات: ' . $e->getMessage();
    } catch (Exception $e) {
        $response['message'] = 'خطأ عام: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'يجب استخدام طريقة POST';
}

echo json_encode($response);
?>