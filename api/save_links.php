<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed');
    }

    $name = $_POST['name'] ?? '';
    $url = $_POST['url'] ?? '';
    $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;
    $icon = $_POST['icon'] ?? 'bi bi-globe';

    if (empty($name) || empty($url)) {
        throw new Exception('Name and URL are required');
    }

    $stmt = $pdo->prepare("INSERT INTO links (name, url, is_published, icon) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $url, $status, $icon]);

    echo json_encode(['success' => true, 'message' => 'تم حفظ الرابط بنجاح']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}