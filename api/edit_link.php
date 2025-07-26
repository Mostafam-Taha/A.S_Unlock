<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // جلب بيانات الرابط للتعديل
        $id = $_GET['id'] ?? null;
        if (!$id) throw new Exception('ID is required');
        
        $stmt = $pdo->prepare("SELECT * FROM links WHERE id = ?");
        $stmt->execute([$id]);
        $link = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$link) throw new Exception('Link not found');
        
        echo json_encode(['success' => true, 'data' => $link]);
    } 
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // حفظ التعديلات
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'] ?? '';
        $url = $_POST['url'] ?? '';
        $status = $_POST['status'] ?? 1;
        $icon = $_POST['icon'] ?? 'bi bi-globe';
        
        if (!$id) throw new Exception('ID is required');
        if (empty($name) || empty($url)) throw new Exception('Name and URL are required');
        
        $stmt = $pdo->prepare("UPDATE links SET name=?, url=?, is_published=?, icon=? WHERE id=?");
        $stmt->execute([$name, $url, $status, $icon, $id]);
        
        echo json_encode(['success' => true, 'message' => 'تم التحديث بنجاح']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}