<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

try {
    // التحقق من أن الطلب POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('الطلب غير مسموح به');
    }

    // معالجة البيانات
    $productName = $_POST['productName'] ?? '';
    $productDescription = $_POST['productDescription'] ?? '';
    $features = $_POST['features'] ?? [];
    $productPrice = floatval($_POST['productPrice'] ?? 0);
    $productDiscount = !empty($_POST['productDiscount']) ? floatval($_POST['productDiscount']) : null;
    $isFeatured = isset($_POST['isFeatured']) ? 1 : 0;
    $isPublished = isset($_POST['isPublished']) ? 1 : 0;

    // معالجة صورة المنتج
    $imagePath = null;
    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileExt = pathinfo($_FILES['productImage']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('product_') . '.' . $fileExt;
        $uploadPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['productImage']['tmp_name'], $uploadPath)) {
            $imagePath = $uploadPath;
        }
    }

    // تحويل المميزات إلى JSON
    $featuresJson = json_encode($features);

    // إدراج البيانات في قاعدة البيانات
    $stmt = $pdo->prepare("
        INSERT INTO digital_products (
            product_name, 
            description, 
            features, 
            price, 
            discount, 
            image_path, 
            is_featured, 
            is_published, 
            created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");

    $stmt->execute([
        $productName,
        $productDescription,
        $featuresJson,
        $productPrice,
        $productDiscount,
        $imagePath,
        $isFeatured,
        $isPublished
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'تم حفظ المنتج بنجاح'
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}