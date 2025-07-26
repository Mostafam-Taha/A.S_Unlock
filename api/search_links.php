<?php
header('Content-Type: application/json');
require_once '../includes/config.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

try {
    $conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $conn->prepare("SELECT * FROM links WHERE name LIKE :search ORDER BY created_at DESC");
    $stmt->bindValue(':search', '%'.$search.'%');
    $stmt->execute();
    
    $links = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // تحويل القيم المنطقية لـ is_published
    foreach ($links as &$link) {
        $link['is_published'] = (bool)$link['is_published'];
    }
    
    echo json_encode([
        'success' => true,
        'data' => $links
    ]);
    
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage()
    ]);
}
?>