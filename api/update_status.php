<?php
require_once '../includes/config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['success' => false, 'message' => 'Invalid request method']));
}

if (!isset($_POST['id']) || !isset($_POST['status'])) {
    die(json_encode(['success' => false, 'message' => 'Missing parameters']));
}

$id = $_POST['id'];
$status = $_POST['status'] === 'accept' ? 'accepted' : 'rejected';
$message = $_POST['message'] ?? '';

try {
    $stmt = $pdo->prepare("UPDATE job_applications SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    
    // يمكنك هنا حفظ الرسالة في قاعدة البيانات إذا أردت
    // $stmt = $pdo->prepare("UPDATE job_applications SET status = ?, admin_message = ? WHERE id = ?");
    // $stmt->execute([$status, $message, $id]);
    
    echo json_encode(['success' => true, 'message' => 'Status updated successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}