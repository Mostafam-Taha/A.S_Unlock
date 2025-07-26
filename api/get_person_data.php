<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

try {
    $personId = $_POST['id'];
    
    // جلب بيانات الشخص
    $stmt = $pdo->prepare("SELECT * FROM persons WHERE id = ?");
    $stmt->execute([$personId]);
    $person = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$person) {
        echo json_encode(['success' => false, 'message' => 'Person not found']);
        exit;
    }
    
    // جلب المهارات
    $stmt = $pdo->prepare("SELECT * FROM person_skills WHERE person_id = ?");
    $stmt->execute([$personId]);
    $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // جلب وسائل التواصل
    $stmt = $pdo->prepare("SELECT * FROM person_social_links WHERE person_id = ?");
    $stmt->execute([$personId]);
    $socialLinks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'person' => $person,
        'skills' => $skills,
        'socialLinks' => $socialLinks
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}