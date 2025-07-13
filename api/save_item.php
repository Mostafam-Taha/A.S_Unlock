<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$response = ['success' => false, 'message' => ''];

try {
    // التحقق من البيانات المطلوبة
    if (empty($_POST['name'])) {
        throw new Exception('Name is required');
    }

    $name = $_POST['name'];
    $description = $_POST['description'] ?? '';
    $audioFile = $_FILES['audio'] ?? null;
    $imageFile = $_FILES['image'] ?? null;

    // التحقق من وجود صورة
    if (!$imageFile || $imageFile['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Image is required');
    }

    // معالجة الصورة وتحويلها إلى WEBP
    $imageInfo = getimagesize($imageFile['tmp_name']);
    if (!$imageInfo) {
        throw new Exception('Invalid image file');
    }

    $mime = $imageInfo['mime'];
    $image = null;

    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($imageFile['tmp_name']);
            break;
        case 'image/png':
            $image = imagecreatefrompng($imageFile['tmp_name']);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($imageFile['tmp_name']);
            break;
        default:
            throw new Exception('Unsupported image type. Please upload JPEG, PNG, or GIF.');
    }

    // إنشاء اسم فريد للصورة
    $imageName = uniqid('img_') . '.webp';
    $imagePath = '../uploads/images/' . $imageName;

    // إنشاء المجلد إذا لم يكن موجوداً
    if (!file_exists('../uploads/images')) {
        mkdir('../uploads/images', 0777, true);
    }

    // حفظ الصورة بصيغة WEBP
    if (!imagewebp($image, $imagePath, 80)) {
        throw new Exception('Failed to save image as WEBP');
    }
    imagedestroy($image);

    // معالجة الصوت إذا تم تحميله
    $audioPath = null;
    if ($audioFile && $audioFile['error'] === UPLOAD_ERR_OK) {
        $audioExt = pathinfo($audioFile['name'], PATHINFO_EXTENSION);
        $allowedAudioExts = ['mp3', 'wav', 'ogg', 'm4a'];
        
        if (!in_array(strtolower($audioExt), $allowedAudioExts)) {
            throw new Exception('Unsupported audio format. Please upload MP3, WAV, M4A or OGG.');
        }

        $audioName = uniqid('audio_') . '.' . $audioExt;
        $audioPath = '../uploads/audio/' . $audioName;

        if (!file_exists('../uploads/audio')) {
            mkdir('../uploads/audio', 0777, true);
        }

        if (!move_uploaded_file($audioFile['tmp_name'], $audioPath)) {
            throw new Exception('Failed to save audio file');
        }
    }

    // إدخال البيانات في قاعدة البيانات
    $stmt = $pdo->prepare("INSERT INTO items (name, description, image_path, audio_path) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $description, $imagePath, $audioPath]);

    $response = [
        'success' => true,
        'message' => 'Item added successfully',
        'data' => [
            'id' => $pdo->lastInsertId(),
            'name' => $name,
            'description' => $description,
            'image_path' => $imagePath,
            'audio_path' => $audioPath
        ]
    ];

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);