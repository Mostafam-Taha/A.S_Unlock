<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

try {
    // التحقق من وجود معرف العنصر المراد حذفه
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        throw new Exception('معرف العنصر غير موجود');
    }

    $id = (int)$_POST['id'];

    // البدء بعملية حذف العنصر
    $pdo->beginTransaction();

    // 1. الحصول على مسار الصورة أولاً
    $stmt = $pdo->prepare("SELECT image_path FROM persons WHERE id = ?");
    $stmt->execute([$id]);
    $person = $stmt->fetch(PDO::FETCH_ASSOC);

    $imagePath = $person['image_path'] ?? null;

    // 2. حذف المهارات المرتبطة
    $stmt = $pdo->prepare("DELETE FROM person_skills WHERE person_id = ?");
    $stmt->execute([$id]);

    // 3. حذف الروابط الاجتماعية المرتبطة
    $stmt = $pdo->prepare("DELETE FROM person_social_links WHERE person_id = ?");
    $stmt->execute([$id]);

    // 4. حذف الشخص من الجدول الرئيسي
    $stmt = $pdo->prepare("DELETE FROM persons WHERE id = ?");
    $stmt->execute([$id]);

    // إذا كان هناك صورة، قم بحذفها
    if ($imagePath && file_exists('../uploads/' . $imagePath)) {
        unlink('../uploads/' . $imagePath);
    }

    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'تم الحذف بنجاح'
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}