<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

try {
    $sql = "SELECT id, title, category, is_published, created_at FROM jobs ORDER BY created_at DESC";
    $stmt = $pdo->query($sql);
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($jobs);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Failed to fetch jobs']);
}