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

// جلب جميع الصور من قاعدة البيانات
$stmt = $pdo->query("SELECT * FROM images ORDER BY created_at DESC");
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض الصور</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }
        .image-card {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
        }
        .image-card img {
            width: 100%;
            height: auto;
        }
        .image-name {
            margin-top: 10px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>معرض الصور</h1>
    <div class="gallery">
        <?php foreach ($images as $image): ?>
            <div class="image-card">
                <img src="uploads/<?php echo htmlspecialchars($image['webp_path']); ?>" alt="<?php echo htmlspecialchars($image['image_name']); ?>" >
                <div class="image-name"><?php echo htmlspecialchars($image['image_name']); ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>