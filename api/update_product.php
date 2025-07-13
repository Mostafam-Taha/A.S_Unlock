<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('الطلب غير مسموح به');
    }

    // التحقق من وجود معرف المنتج
    $productId = $_POST['product_id'] ?? null;
    if (!$productId) {
        throw new Exception('معرف المنتج غير موجود');
    }

    // معالجة البيانات الأساسية
    $productName = $_POST['product_name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = floatval($_POST['price'] ?? 0);
    $discount = !empty($_POST['discount']) ? floatval($_POST['discount']) : null;
    $instructions = $_POST['instructions'] ?? '';
    $isPublished = isset($_POST['is_published']) ? 1 : 0;
    $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
    $isSpecialOffer = isset($_POST['is_special_offer']) ? 1 : 0;
    
    // معالجة المميزات
    $features = $_POST['features'] ?? [];
    $featuresJson = json_encode(array_values($features));
    
    // معالجة صورة المنتج
    $imagePath = $_POST['current_image'] ?? null;
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // حذف الصورة القديمة إذا وجدت
        if ($imagePath && file_exists($imagePath)) {
            unlink($imagePath);
        }

        $fileExt = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('product_') . '.' . $fileExt;
        $uploadPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadPath)) {
            $imagePath = $uploadPath;
        }
    }
    
    // تحديث المنتج في قاعدة البيانات
    $stmt = $pdo->prepare("
        UPDATE digital_products SET
            product_name = ?,
            description = ?,
            price = ?,
            discount = ?,
            image_path = ?,
            features = ?,
            instructions = ?,
            is_published = ?,
            is_featured = ?,
            is_special_offer = ?,
            updated_at = NOW()
        WHERE id = ?
    ");
    
    $stmt->execute([
        $productName,
        $description,
        $price,
        $discount,
        $imagePath,
        $featuresJson,
        $instructions,
        $isPublished,
        $isFeatured,
        $isSpecialOffer,
        $productId
    ]);
    
    // معالجة بيانات الخطة
    $planType = $_POST['plan_type'] ?? null;
    $planName = $_POST['plan_name'] ?? '';
    $planPrice = !empty($_POST['plan_price']) ? floatval($_POST['plan_price']) : 0;
    $planFeatures = $_POST['plan_features'] ?? [];
    $planFeaturesJson = json_encode(array_values($planFeatures));
    
    if ($planType) {
        $stmt = $pdo->prepare("
            INSERT INTO product_plans (
                product_id,
                plan_type,
                plan_name,
                plan_price,
                plan_features,
                created_at
            ) VALUES (?, ?, ?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE
                plan_type = VALUES(plan_type),
                plan_name = VALUES(plan_name),
                plan_price = VALUES(plan_price),
                plan_features = VALUES(plan_features),
                updated_at = NOW()
        ");
        
        $stmt->execute([
            $productId,
            $planType,
            $planName,
            $planPrice,
            $planFeaturesJson
        ]);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'تم تحديث المنتج بنجاح'
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}