<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
        throw new Exception('Invalid request');
    }

    $jobId = $_POST['id'];
    $newStatus = ($_POST['action'] === 'publish') ? 1 : 0;
    
    $sql = "UPDATE jobs SET is_published = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$newStatus, $jobId]);
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}