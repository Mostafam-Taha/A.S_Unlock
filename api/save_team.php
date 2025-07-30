<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'as_unlock';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    die(json_encode(['success' => false, 'message' => 'Could not connect to the database']));
}

// معالجة رفع الصورة
$uploadDir = '../uploads/team/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$imageName = '';
if (isset($_FILES['teamImage']) && $_FILES['teamImage']['error'] === UPLOAD_ERR_OK) {
    $fileExt = pathinfo($_FILES['teamImage']['name'], PATHINFO_EXTENSION);
    $imageName = uniqid() . '.' . $fileExt;
    $targetPath = $uploadDir . $imageName;
    
    if (!move_uploaded_file($_FILES['teamImage']['tmp_name'], $targetPath)) {
        echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Image upload error']);
    exit;
}

// الحصول على البيانات من النموذج
$name = $_POST['teamName'] ?? '';
$jobTitle = $_POST['jobTitle'] ?? '';

if (empty($name) || empty($jobTitle)) {
    // إذا فشل التحقق، احذف الصورة التي تم رفعها
    if (!empty($imageName) && file_exists($uploadDir . $imageName)) {
        unlink($uploadDir . $imageName);
    }
    echo json_encode(['success' => false, 'message' => 'Name and job title are required']);
    exit;
}

// إدراج البيانات في قاعدة البيانات
try {
    $stmt = $pdo->prepare("INSERT INTO teams (name, job_title, image_path) VALUES (:name, :job_title, :image_path)");
    $stmt->execute([
        ':name' => $name,
        ':job_title' => $jobTitle,
        ':image_path' => $imageName
    ]);
    
    echo json_encode(['success' => true, 'message' => 'Team member added successfully']);
} catch (PDOException $e) {
    // في حالة الخطأ، احذف الصورة التي تم رفعها
    if (!empty($imageName) && file_exists($uploadDir . $imageName)) {
        unlink($uploadDir . $imageName);
    }
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}