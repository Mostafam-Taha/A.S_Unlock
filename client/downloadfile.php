<?php
require_once '../includes/config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $stmt = $pdo->prepare("SELECT * FROM uploads WHERE id = ? AND status = 'completed' AND file_path IS NOT NULL");
    $stmt->execute([$id]);
    $file = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($file && file_exists($file['file_path'])) {
        // تحديث عدد مرات التحميل إذا كنت تريد تتبع ذلك
        // $pdo->prepare("UPDATE uploads SET download_count = download_count + 1 WHERE id = ?")->execute([$id]);
        
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file['file_name']).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file['file_path']));
        flush(); // Flush system output buffer
        readfile($file['file_path']);
        exit;
    }
}

// إذا لم يتم العثور على الملف
header("HTTP/1.0 404 Not Found");
die('الملف غير موجود أو غير متاح للتحميل');
?>