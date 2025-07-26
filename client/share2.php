<?php
require_once '../includes/config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $stmt = $pdo->prepare("SELECT * FROM uploads WHERE id = ? AND status = 'completed'");
    $stmt->execute([$id]);
    $file = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($file) {
        // يمكنك هنا عرض صفحة جميلة للمشاركة
        // مع إمكانية مشاركة عبر وسائل التواصل الاجتماعي
        // هذا مثال بسيط
        
        $pageTitle = $file['file_path'] ? $file['file_name'] : $file['link_name'];
        ?>
        <!DOCTYPE html>
        <html lang="ar" dir="rtl">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>مشاركة <?php echo htmlspecialchars($pageTitle); ?></title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="../assets/css/share.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
            <style>
                body { padding: 20px; }
                .share-box { max-width: 600px; margin: 0 auto; text-align: center; }
                .share-icon { font-size: 3rem; margin-bottom: 20px; }
                .share-links { margin-top: 30px; }
                .share-links a { margin: 0 10px; font-size: 2rem; }
            </style>
        </head>
        <body>
            <div class="share-box">
                <i class="bi bi-share-fill share-icon"></i>
                <h1>مشاركة <?php echo htmlspecialchars($pageTitle); ?></h1>
                <p>يمكنك مشاركة هذا الملف مع الآخرين عبر الروابط التالية:</p>
                
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="shareLink" 
                           value="<?php echo (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/client/share.php?id=' . $id; ?>" 
                           readonly>
                    <button class="btn btn-outline-secondary" type="button" onclick="copyShareLink()">نسخ الرابط</button>
                </div>
                
                <div class="share-links">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/client/share.php?id=' . $id); ?>" 
                       target="_blank" class="text-primary"><i class="bi bi-facebook"></i></a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/client/share.php?id=' . $id); ?>" 
                       target="_blank" class="text-info"><i class="bi bi-twitter"></i></a>
                    <a href="whatsapp://send?text=<?php echo urlencode('تحميل ' . $pageTitle . ': ' . (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/client/share.php?id=' . $id); ?>" 
                       target="_blank" class="text-success"><i class="bi bi-whatsapp"></i></a>
                </div>
                
                <?php if ($file['file_path']): ?>
                    <a href="download.php?id=<?php echo $id; ?>" class="btn btn-primary mt-3">
                        <i class="bi bi-download"></i> تحميل الملف
                    </a>
                <?php else: ?>
                    <a href="<?php echo htmlspecialchars($file['link_url']); ?>" target="_blank" class="btn btn-primary mt-3">
                        <i class="bi bi-box-arrow-up-right"></i> فتح الرابط
                    </a>
                <?php endif; ?>
            </div>
            
            <script>
                function copyShareLink() {
                    const copyText = document.getElementById("shareLink");
                    copyText.select();
                    copyText.setSelectionRange(0, 99999);
                    document.execCommand("copy");
                    alert("تم نسخ الرابط: " + copyText.value);
                }
            </script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script src="../assets/js/dark-mode.js"></script>
        </body>
        </html>
        <?php
        exit;
    }
}

// إذا لم يتم العثور على الملف
header("HTTP/1.0 404 Not Found");
die('الملف غير موجود أو غير متاح للمشاركة');
?>