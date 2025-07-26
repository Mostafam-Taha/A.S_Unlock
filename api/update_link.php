<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }

    $id = $_POST['id'] ?? null;
    $name = $_POST['name'] ?? '';
    $url = $_POST['url'] ?? '';
    $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;
    $icon = $_POST['icon'] ?? 'bi bi-globe';

    if (empty($id)) {
        throw new Exception('ID is required for update');
    }

    if (empty($name) || empty($url)) {
        throw new Exception('Name and URL are required');
    }

    $stmt = $pdo->prepare("UPDATE links SET name = ?, url = ?, is_published = ?, icon = ? WHERE id = ?");
    $stmt->execute([$name, $url, $status, $icon, $id]);

    echo json_encode(['success' => true, 'message' => 'تم تحديث الرابط بنجاح']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}