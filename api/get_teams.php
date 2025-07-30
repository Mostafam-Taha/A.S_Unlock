<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT id, name, job_title, image_path, created_at FROM teams ORDER BY created_at DESC");
    $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['success' => true, 'data' => $teams]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}