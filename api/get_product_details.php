<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

try {
    if (!isset($_GET['id'])) {
        throw new Exception('معرف المنتج غير موجود');
    }
    
    $productId = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM digital_products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        throw new Exception('المنتج غير موجود');
    }
    
    echo json_encode($product);
    
} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}