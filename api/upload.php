<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

// إنشاء مجلد التحميلات إذا لم يكن موجوداً
$uploadDir = '../uploads/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$response = ['success' => false, 'message' => ''];

try {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'upload':
            handleFileUpload();
            break;
            
        case 'cancel':
            handleUploadCancel();
            break;
            
        case 'save_files':
            saveFilesToDatabase();
            break;
            
        case 'save_link':
            saveLinkToDatabase();
            break;
            
        default:
            $response['message'] = 'Invalid action';
            echo json_encode($response);
            exit;
    }
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
    echo json_encode($response);
}

function handleFileUpload() {
    global $uploadDir, $response;
    
    if (empty($_FILES['file'])) {
        $response['message'] = 'No file uploaded';
        echo json_encode($response);
        exit;
    }
    
    $file = $_FILES['file'];
    $fileId = $_POST['fileId'];
    $fileName = basename($file['name']);
    $filePath = $uploadDir . $fileId . '_' . $fileName;
    $fileSize = $file['size'];
    $fileType = $file['type'];
    
    // التحقق من وجود الملف جزئياً (للاستئناف)
    if (file_exists($filePath)) {
        $uploadedSize = filesize($filePath);
    } else {
        $uploadedSize = 0;
    }
    
    // فتح الملف للكتابة (الاستئناف إذا كان موجوداً)
    $out = fopen($filePath, $uploadedSize ? 'ab' : 'wb');
    if (!$out) {
        $response['message'] = 'Failed to open output stream';
        echo json_encode($response);
        exit;
    }
    
    // فتح الملف المرسل للقراءة
    $in = fopen($file['tmp_name'], 'rb');
    if (!$in) {
        fclose($out);
        $response['message'] = 'Failed to open input stream';
        echo json_encode($response);
        exit;
    }
    
    // تخطي الجزء الذي تم تحميله مسبقاً
    if ($uploadedSize) {
        fseek($in, $uploadedSize);
    }
    
    // نسخ المحتوى
    while ($buff = fread($in, 4096)) {
        fwrite($out, $buff);
    }
    
    fclose($in);
    fclose($out);
    
    // حذف الملف المؤقت
    unlink($file['tmp_name']);
    
    $response['success'] = true;
    $response['fileId'] = $fileId;
    $response['filePath'] = $filePath;
    $response['fileName'] = $fileName;
    $response['fileSize'] = $fileSize;
    $response['fileType'] = $fileType;
    
    echo json_encode($response);
}

function handleUploadCancel() {
    global $uploadDir, $response;
    
    $fileId = $_POST['fileId'] ?? '';
    if (!$fileId) {
        $response['message'] = 'No file ID provided';
        echo json_encode($response);
        exit;
    }
    
    // البحث عن الملفات الجزئية وحذفها
    foreach (glob($uploadDir . $fileId . '_*') as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    
    $response['success'] = true;
    echo json_encode($response);
}

function saveFilesToDatabase() {
    global $pdo, $uploadDir, $response;
    
    $fileIds = $_POST['fileIds'] ?? [];
    if (empty($fileIds)) {
        $response['message'] = 'No files to save';
        echo json_encode($response);
        exit;
    }
    
    try {
        $pdo->beginTransaction();
        
        foreach ($fileIds as $fileId) {
            // البحث عن الملف في مجلد التحميلات
            $files = glob($uploadDir . $fileId . '_*');
            if (empty($files)) continue;
            
            $filePath = $files[0];
            $fileName = substr(basename($filePath), strlen($fileId) + 1);
            $fileSize = filesize($filePath);
            $fileType = mime_content_type($filePath);
            
            // إدخال البيانات في قاعدة البيانات
            $stmt = $pdo->prepare("INSERT INTO uploads 
                (file_name, file_path, file_size, file_type, upload_date, status) 
                VALUES (?, ?, ?, ?, NOW(), 'completed')");
            
            $stmt->execute([
                $fileName,
                $filePath,
                $fileSize,
                $fileType
            ]);
        }
        
        $pdo->commit();
        $response['success'] = true;
    } catch (Exception $e) {
        $pdo->rollBack();
        $response['message'] = 'Database error: ' . $e->getMessage();
    }
    
    echo json_encode($response);
}

function saveLinkToDatabase() {
    global $pdo, $response;
    
    $linkUrl = $_POST['link_url'] ?? '';
    $linkName = $_POST['link_name'] ?? '';
    $linkSize = $_POST['link_size'] ?? 0;
    $linkDescription = $_POST['link_description'] ?? '';
    
    if (empty($linkUrl)) {
        $response['message'] = 'Link URL is required';
        echo json_encode($response);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO uploads 
            (link_url, link_name, link_size, link_description, upload_date, status) 
            VALUES (?, ?, ?, ?, NOW(), 'completed')");
        
        $stmt->execute([
            $linkUrl,
            $linkName,
            $linkSize,
            $linkDescription
        ]);
        
        $response['success'] = true;
    } catch (Exception $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
    }
    
    echo json_encode($response);
}
?>