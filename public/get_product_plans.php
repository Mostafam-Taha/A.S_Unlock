<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

if (!isset($_GET['product_id'])) {
    echo json_encode([]);
    exit;
}

$productId = (int)$_GET['product_id'];
$stmt = $pdo->prepare("
    SELECT p.id as plan_id, p.*, d.instructions 
    FROM product_plans p
    LEFT JOIN digital_products d ON p.product_id = d.id
    WHERE p.product_id = ?
");
$stmt->execute([$productId]);
$plans = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($plans);
?>