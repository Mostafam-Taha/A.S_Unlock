<?php
// ملف get_storage.php
require_once '../includes/config.php';

header('Content-Type: application/json');

try {
    // حساب المساحة المستخدمة للملفات
    $stmt = $pdo->query("SELECT SUM(file_size) AS total_size FROM uploads WHERE file_size > 0");
    $fileSize = $stmt->fetchColumn() ?? 0;
    
    // حساب المساحة المستخدمة للروابط (إذا كانت link_size تحوي القيمة بالميجابايت)
    $stmt = $pdo->query("SELECT SUM(link_size) AS total_size FROM uploads WHERE link_size IS NOT NULL");
    $linkSize = ($stmt->fetchColumn() ?? 0) * 1024 * 1024; // تحويل من MB إلى بايت
    
    $totalUsed = $fileSize + $linkSize;
    $totalSpace = 5 * 1024 * 1024 * 1024; // 90GB بالبايت
    
    echo json_encode([
        'success' => true,
        'used' => $totalUsed,
        'total' => $totalSpace,
        'used_gb' => round($totalUsed / (1024 * 1024 * 1024), 2),
        'total_gb' => round($totalSpace / (1024 * 1024 * 1024), 2),
        'percentage' => round(($totalUsed / $totalSpace) * 100, 2)
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to calculate storage: ' . $e->getMessage()
    ]);
}
?>