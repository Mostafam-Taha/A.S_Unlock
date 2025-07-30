<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('الطلب غير مسموح به');
    }

    $productId = $_POST['product_id'] ?? null;
    if (!$productId) {
        throw new Exception('معرف المنتج غير موجود');
    }

    // معالجة بيانات المنتج الأساسية
    $productName = $_POST['product_name'] ?? '';
    $serviceType = $_POST['service_type'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = floatval($_POST['price'] ?? 0);
    $discount = !empty($_POST['discount']) ? floatval($_POST['discount']) : null;
    $instructions = $_POST['instructions'] ?? '';
    $isPublished = isset($_POST['is_published']) ? 1 : 0;
    $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
    $isSpecialOffer = isset($_POST['is_special_offer']) ? 1 : 0;
    $warrantyDurationDays = !empty($_POST['warranty_duration_days']) ? (int)$_POST['warranty_duration_days'] : null;
    
    $features = $_POST['features'] ?? [];
    $featuresJson = json_encode(array_values(array_filter($features)));
    
    // معالجة صورة المنتج
    $imagePath = $_POST['current_image'] ?? null;
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

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
    
    // بدء المعاملة
    $pdo->beginTransaction();
    
    // تحديث بيانات المنتج الأساسية (تم إضافة حقل warranty_duration_days هنا)
    $stmt = $pdo->prepare("
        UPDATE digital_products SET
            product_name = ?,
            service_type = ?,
            description = ?,
            price = ?,
            discount = ?,
            image_path = ?,
            features = ?,
            instructions = ?,
            is_published = ?,
            is_featured = ?,
            is_special_offer = ?,
            warranty_duration_days = ?,
            updated_at = NOW()
        WHERE id = ?
    ");
    
    $stmt->execute([
        $productName,
        $serviceType,
        $description,
        $price,
        $discount,
        $imagePath,
        $featuresJson,
        $instructions,
        $isPublished,
        $isFeatured,
        $isSpecialOffer,
        $warrantyDurationDays,
        $productId
    ]);
    
    // معالجة خطط المنتج
    $planIds = $_POST['plan_ids'] ?? [];
    $planTypes = $_POST['plan_types'] ?? [];
    $planNames = $_POST['plan_names'] ?? [];
    $planPrices = $_POST['plan_prices'] ?? [];
    $allPlanFeatures = $_POST['plan_features'] ?? [];
    
    // الحصول على خطط المنتج الحالية
    $currentPlans = [];
    $stmt = $pdo->prepare("SELECT id FROM product_plans WHERE product_id = ?");
    $stmt->execute([$productId]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $currentPlans[] = $row['id'];
    }
    
    // معالجة الخطط المرسلة
    $submittedPlans = [];
    foreach ($planIds as $index => $planId) {
        if (empty($planTypes[$index]) || empty($planNames[$index]) || !isset($planPrices[$index])) {
            continue; // تخطي الخطط غير المكتملة
        }
        
        $planType = $planTypes[$index];
        $planName = $planNames[$index];
        $planPrice = floatval($planPrices[$index]);
        $planFeatures = $allPlanFeatures[$planId] ?? [];
        $planFeaturesJson = json_encode(array_values(array_filter($planFeatures)));
        
        if (is_numeric($planId)) {
            // خطة موجودة - تحديث
            $stmt = $pdo->prepare("
                UPDATE product_plans SET
                    plan_type = ?,
                    plan_name = ?,
                    plan_price = ?,
                    plan_features = ?,
                    updated_at = NOW()
                WHERE id = ? AND product_id = ?
            ");
            $stmt->execute([
                $planType,
                $planName,
                $planPrice,
                $planFeaturesJson,
                $planId,
                $productId
            ]);
            
            $submittedPlans[] = $planId;
        } else {
            // خطة جديدة - إضافة
            $stmt = $pdo->prepare("
                INSERT INTO product_plans (
                    product_id,
                    plan_type,
                    plan_name,
                    plan_price,
                    plan_features,
                    created_at
                ) VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([
                $productId,
                $planType,
                $planName,
                $planPrice,
                $planFeaturesJson
            ]);
            
            $submittedPlans[] = $pdo->lastInsertId();
        }
    }
    
    // حذف الخطط التي لم تعد موجودة
    $plansToDelete = array_diff($currentPlans, $submittedPlans);
    if (!empty($plansToDelete)) {
        $placeholders = implode(',', array_fill(0, count($plansToDelete), '?'));
        $stmt = $pdo->prepare("DELETE FROM product_plans WHERE id IN ($placeholders)");
        $stmt->execute(array_values($plansToDelete));
    }
    
    // إتمام المعاملة
    $pdo->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'تم تحديث المنتج وخططه بنجاح'
    ]);
    
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}