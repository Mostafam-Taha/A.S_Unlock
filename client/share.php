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
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
            <style>
                :root {
                    --br-primary-color: linear-gradient(90deg, #1976D2, #42A5F5);
                    --br-color-h-p: #1976D2;
                    --br-sacn-color: #23234a;
                    --br-links-color: #495057;
                    --br-border-color: #dfe1e5;
                    --br-btn-padding: 7px 22px;
                    --br-font-w-text: 400;
                }

                * {
                    margin: 0;
                    padding: 0;
                    font-family: "Tajawal", sans-serif;
                    box-sizing: border-box;
                }

                html {
                    scroll-behavior: smooth;
                }

                body {
                    font-family: "Tajawal", sans-serif;
                    min-height: 100vh;
                    background-color: #f8f9fa;
                    justify-content: center;
                    align-items: center;
                    padding: 20px;
                    line-height: 1.6;
                }

                h1, h2, h3, h4, h5, h6 {
                    color: var(--br-sacn-color);
                    font-weight: 600;
                }

                p {
                    color: var(--br-sacn-color);
                    opacity: 0.8;
                    font-size: 0.95rem;
                }

                .share-box {
                    background: white;
                    border-radius: 15px;
                    padding: 40px;
                    width: 100%;
                    max-width: 500px;
                    text-align: center;
                    border: 1px solid #f0f0f0;
                    margin: 0 auto;
                }

                .share-icon {
                    font-size: 3rem;
                    margin-bottom: 15px;
                    color: #1976D2;
                }

                .share-box h1 {
                    font-size: 1.6rem;
                    margin-bottom: 12px;
                }

                .share-box p {
                    margin-bottom: 25px;
                    color: #555;
                }

                .input-group {
                    margin-bottom: 25px;
                    border-radius: 8px;
                    overflow: hidden;
                    border: 1px solid #e0e0e0;
                }

                .form-control {
                    border: none;
                    padding: 12px 15px;
                    font-size: 0.9rem;
                }

                .form-control:focus {
                    box-shadow: none;
                }

                .btn-outline-secondary {
                    background: #f8f9fa;
                    border: none;
                    border-left: 1px solid #e0e0e0;
                    color: #495057;
                    padding: 12px 20px;
                    transition: all 0.2s ease;
                }

                .btn-outline-secondary:hover {
                    background: #e9ecef;
                }

                .share-links {
                    display: flex;
                    justify-content: center;
                    gap: 15px;
                    margin-bottom: 30px;
                }

                .share-links a {
                    width: 44px;
                    height: 44px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 1.3rem;
                    transition: all 0.2s ease;
                    background: #f5f5f5;
                    color: #555;
                }

                .share-links a:hover {
                    transform: scale(1.1);
                    color: white;
                }

                .share-links a.text-primary:hover {
                    background: #1877f2;
                }

                .share-links a.text-info:hover {
                    background: #1da1f2;
                }

                .share-links a.text-success:hover {
                    background: #25d366;
                }

                .btn-primary {
                    background: var(--br-primary-color);
                    border: none;
                    padding: 12px 28px;
                    font-size: 1rem;
                    font-weight: 500;
                    border-radius: 8px;
                    transition: all 0.2s ease;
                    color: white;
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                }

                .btn-primary:hover {
                    opacity: 0.9;
                    transform: translateY(-2px);
                    color: white;
                }

                @media (max-width: 576px) {
                    .share-box {
                        padding: 30px 20px;
                    }
                    
                    .share-box h1 {
                        font-size: 1.4rem;
                    }
                    
                    .share-icon {
                        font-size: 2.5rem;
                    }
                }
            </style>
        </head>
        <body>
            <div class="share-box">
                <i class="bi bi-share-fill share-icon"></i>
                <h1>مشاركة <?php echo htmlspecialchars($pageTitle); ?></h1>
                <p>يمكنك مشاركة هذا الملف مع الآخرين عبر الروابط التالية:</p>
                
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="shareLink" 
                           value="<?php echo (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/share.php?id=' . $id; ?>" 
                           readonly>
                    <button class="btn btn-outline-secondary" type="button" onclick="copyShareLink()">نسخ الرابط</button>
                </div>
                
                <div class="share-links">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/share.php?id=' . $id); ?>" 
                       target="_blank" class="text-primary"><i class="bi bi-facebook"></i></a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/share.php?id=' . $id); ?>" 
                       target="_blank" class="text-info"><i class="bi bi-twitter"></i></a>
                    <a href="whatsapp://send?text=<?php echo urlencode('تحميل ' . $pageTitle . ': ' . (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/share.php?id=' . $id); ?>" 
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