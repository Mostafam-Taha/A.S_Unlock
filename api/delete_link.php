<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }

    $id = $_POST['id'] ?? null;
    
    if (!$id) {
        throw new Exception('ID is required');
    }

    $stmt = $pdo->prepare("DELETE FROM links WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true, 'message' => 'تم حذف الرابط بنجاح']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}