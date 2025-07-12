<?php
// اتصال بقاعدة البيانات
$host = 'localhost';
$dbname = 'as_unlock';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// معالجة رفع الصورة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $imageName = $_POST['image_name'];
    $uploadDir = 'uploads/';
    
    // إنشاء المجلد إذا لم يكن موجوداً
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // معلومات الملف المرفوع
    $originalName = $_FILES['image']['name'];
    $tmpName = $_FILES['image']['tmp_name'];
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
    
    // إنشاء اسم فريد للملف
    $uniqueName = uniqid() . '.webp';
    $webpPath = $uploadDir . $uniqueName;
    
    // تحويل الصورة إلى WEBP
    if ($extension === 'jpg' || $extension === 'jpeg') {
        $image = imagecreatefromjpeg($tmpName);
    } elseif ($extension === 'png') {
        $image = imagecreatefrompng($tmpName);
    } else {
        die("صيغة الصورة غير مدعومة. يرجى رفع صورة JPEG أو PNG.");
    }
    
    // حفظ الصورة بصيغة WEBP
    if (imagewebp($image, $webpPath, 80)) {
        // حفظ المعلومات في قاعدة البيانات
        $stmt = $pdo->prepare("INSERT INTO images (image_name, image_path, webp_path) VALUES (?, ?, ?)");
        $stmt->execute([$imageName, $originalName, $uniqueName]);
        
        echo "تم رفع الصورة بنجاح وتحويلها إلى WEBP!";
    } else {
        echo "حدث خطأ أثناء تحويل الصورة إلى WEBP.";
    }
    
    // تحرير الذاكرة
    imagedestroy($image);
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رفع الصور</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="file"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>رفع صورة جديدة</h1>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="image_name">اسم الصورة:</label>
                <input type="text" id="image_name" name="image_name" required>
            </div>
            <div class="form-group">
                <label for="image">اختر الصورة:</label>
                <input type="file" id="image" name="image" accept="image/jpeg, image/png" required>
            </div>
            <button type="submit">رفع الصورة</button>
        </form>
    </div>
</body>
</html>