<?php
require_once '../includes/config.php'; // استيراد ملف الاتصال بقاعدة البيانات

// استعلام لاسترجاع الملفات من قاعدة البيانات
$stmt = $pdo->query("SELECT * FROM uploads WHERE status = 'completed' ORDER BY upload_date DESC");
$files = $stmt->fetchAll(PDO::FETCH_ASSOC);

// دالة لتحويل الحجم إلى صيغة مقروءة
function formatSizeUnits($bytes) {
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }
    return $bytes;
}

// دالة لتحديد أيقونة حسب نوع الملف
function getFileIcon($fileType) {
    $fileType = strtolower($fileType);
    $icons = [
        'pdf' => 'bi-file-earmark-pdf-fill',
        'doc' => 'bi-file-earmark-word-fill',
        'docx' => 'bi-file-earmark-word-fill',
        'xls' => 'bi-file-earmark-excel-fill',
        'xlsx' => 'bi-file-earmark-excel-fill',
        'ppt' => 'bi-file-earmark-ppt-fill',
        'pptx' => 'bi-file-earmark-ppt-fill',
        'zip' => 'bi-file-earmark-zip-fill',
        'rar' => 'bi-file-earmark-zip-fill',
        '7z' => 'bi-file-earmark-zip-fill',
        'exe' => 'bi-filetype-exe',
        'jpg' => 'bi-file-earmark-image-fill',
        'jpeg' => 'bi-file-earmark-image-fill',
        'png' => 'bi-file-earmark-image-fill',
        'gif' => 'bi-file-earmark-image-fill',
        'mp3' => 'bi-file-earmark-music-fill',
        'wav' => 'bi-file-earmark-music-fill',
        'mp4' => 'bi-file-earmark-play-fill',
        'avi' => 'bi-file-earmark-play-fill',
        'mkv' => 'bi-file-earmark-play-fill',
        'txt' => 'bi-file-earmark-text-fill',
    ];
    
    foreach ($icons as $ext => $icon) {
        if (strpos($fileType, $ext) !== false) {
            return $icon;
        }
    }
    
    return 'bi-file-earmark-fill'; // أيقونة افتراضية
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Primary -->
    <meta name="title" content="A.S UNLOCK" />
    <meta name="description" content="A.S UNLOCK - خبراء فتح وإصلاح أجهزة التابلت بأحدث التقنيات. خدمات سريعة ومضمونة مع دعم على مدار الساعة." />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://as_unlock.ct.ws" />
    <meta property="og:title" content="A.S UNLOCK" />
    <meta property="og:description" content="A.S UNLOCK - خبراء فتح وإصلاح أجهزة التابلت بأحدث التقنيات. خدمات سريعة ومضمونة مع دعم على مدار الساعة." />
    <meta property="og:image" content="https://as_unlock.ct.ws/" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="https://as_unlock.ct.ws/assets/image/favicon.ico" />
    <meta property="twitter:title" content="A.S UNLOCK" />
    <meta property="twitter:description" content="A.S UNLOCK - خبراء فتح وإصلاح أجهزة التابلت بأحدث التقنيات. خدمات سريعة ومضمونة مع دعم على مدار الساعة." />
    <meta property="twitter:image" content="https://as_unlock.ct.ws/assets/image/favicon.ico" />

    <!-- Links -->
    <link rel="icon" type="image/x-icon" href="../assets/image/favicon.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap"rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/download.css">

    <title>A.S UNLOCK</title>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="logo">
            <img src="../assets/image/favicon.ico" alt="Not Found Logo" loading="eager">
            <h1>A.S UNLOCK</h1>
        </div>
        
        <div class="hamburger" id="hamburger">
            <span></span>
            <span></span>
            <span></span>
        </div>
        
        <nav class="navbar" id="navbar">
            <ul class="links">
                <li><a href="../public/index.php">الرئيسية</a></li>
                <li><a href="../public/products.php">المنتجات</a></li>
                <li><a href="#" class="fexid-btn" style="color: #1976D2;">التحميلات</a></li>
                <li><a href="#">الضمان</a></li>
                <li><a href="#">فريق العمل</a></li>
            </ul>
            <ul class="btn">
                <li><a href="https://wa.me/201069062005?text=السلام%20عليكم%20ورحمة%20الله%20وبركاته%0Aعاوز%20استفسر%20عن%20" class="contact co-btn" target="_blank">أتصل بنا <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z" /></svg></a></li>
            </ul>
        </nav>
    </header>
    <!-- Download -->
    <!-- main Download Staps(1, 2 , 3, 4) -->
    <main class="download-staps">
        <div class="tab-staps">
            <div class="index-bor a"><div class="text-stap">1</div></div>
            <div class="index-bor b"><div class="text-stap">2</div></div>
            <div class="index-bor c"><div class="text-stap">3</div></div>
            <div class="index-bor d"><div class="text-stap">4</div></div>
        </div>
        <div class="section">
            <!-- Section 1 -->
            <section class="download-ultra-viewer" id="DownloadUltraViewer">
                <h1>حميل <span style="color: #1976D2;">Ultar Viewer</span></h1>
                <div class="container">
                    <div class="text-section">
                        <h1>Ultar Viewer</h1>
                        <p>تطبيق سطح مكتب بعيد يسمح لك بالتحكم في جهاز الحاسوب الخاص بك</p>
                        <div class="type-size-ul">
                            <div class="type">Application/ exe</div>
                            <div class="size">3.47 MB</div>
                        </div>
                        <div class="download-file-ultar"><a href="https://www.ultraviewer.net/en/download.html">تحميل</a></div>
                    </div>
                    <div class="img-section">
                        <img src="../assets/image/ultraviewer.png" alt="ultraviewer" loading="lazy">
                    </div>
                </div>
            </section>
            <!-- Section 2 -->
            <section class="download-files" id="DownloadFiles">
                <h1>اختر ملف التحميل</h1>
                <div class="container">
                    <?php if (empty($files)): ?>
                        <div class="d-flex flex-column align-items-center justify-content-center py-5 my-5 text-center">
                            <i class="bi bi-folder-x" style="font-size: 5rem; color: #6c757d;"></i>
                            <h3 class="mt-3">لا توجد ملفات متاحة للتحميل حالياً</h3>
                            <p class="text-muted">سيتم عرض الملفات هنا بمجرد توفرها</p>
                        </div>
                    <?php else: ?>
                        <div class="downloads-file">
                            <div class="device-down">
                                <?php foreach ($files as $file): ?>
                                    <div class="card-download">
                                        <?php if ($file['file_path']): // إذا كان ملف ?>
                                            <i class="bi <?php echo getFileIcon($file['file_type']); ?>"></i>
                                            <h4><?php echo htmlspecialchars($file['file_name']); ?></h4>
                                            <div class="size-type">
                                                <span class="size"><?php echo formatSizeUnits($file['file_size']); ?></span>
                                                <span class="type"><?php echo strtoupper($file['file_type']); ?></span>
                                            </div>
                                            <div class="option-link">
                                                <div><a href="download1.php?id=<?php echo $file['id']; ?>" title="تحميل"><i class="bi bi-download"></i></a></div>
                                                <div><a href="share.php?id=<?php echo $file['id']; ?>" title="مشاركة"><i class="bi bi-share"></i></a></div>
                                            </div>
                                        <?php else: // إذا كان رابط ?>
                                            <i class="bi bi-link-45deg"></i>
                                            <h4><?php echo htmlspecialchars($file['link_name']); ?></h4>
                                            <div class="size-type">
                                                <span class="size"><?php echo formatSizeUnits($file['link_size']); ?></span>
                                                <span class="type">رابط</span>
                                            </div>
                                            <div class="option-link">
                                                <div><a href="<?php echo htmlspecialchars($file['link_url']); ?>" target="_blank" title="فتح الرابط"><i class="bi bi-box-arrow-up-right"></i></a></div>
                                                <div><a href="share.php?id=<?php echo $file['id']; ?>" title="مشاركة"><i class="bi bi-share"></i></a></div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
            <!-- Section 3 -->
            <section class="zip-files-size" id="ZipFilesSize">
                <h1>حمل برنامج فك الضغط</h1>
                <div class="container">
                    <div class="img-file">
                        <img src="../assets/image/rarlab-winrar.png" alt="لا توجد صورة" loading="lazy">
                    </div>
                    <div class="link-download">
                        <h1> WinRAR أو 7-Zip قم بفك ضغط الملف باستخدام برنامج</h1>
                        <div class="link-down">
                            <a href="https://www.win-rar.com/download.html?&L=0">تحميل WinRAR</a>
                            <a href="https://www.7-zip.org/">تحميل Zip-7</a>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Section 4 -->
            <section class="contact-us-team" id="ContactUsTeam">
                <h1>تواصل مع الدعم</h1>
                <div class="container">
                    <div class="massage-user">
                        <div class="img-user"><i class="bi bi-person"></i></div>
                        <div class="text-user">
                            <div class="name">Mostafa</div>
                            <div class="massage">
                                <span>أسلام عليكم ورحمة الله وبركاتة, اريد الاستفسار عن ...</span>
                            </div>
                        </div>
                    </div>
                    <div class="massage-team">
                        <div class="img-team">
                            <img src="../assets/image/favicon.ico" alt="logo-team" loading="lazy">
                        </div>
                        <div class="text-team">
                            <div class="name-team">Ahmed</div>
                            <div class="massage">وعليكم السلام ورحمة الله وبركاتة, اتفضل اكتب استفسارك</div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <main class="section-bottom"> 
        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="grid-website">
                    <div class="logo">
                        <img src="../assets/image/favicon.ico" alt="Not Found Icon" loading="lazy">
                        <h1>A.S UNLOCK</h1>
                    </div>
                    <div class="description"><p>خبراء السوفت وير وخدمات التابلت التعليمية والدعم الفني السريع.</p></div>
                </div>
                <div class="quick-links">
                    <ul class="links">
                        <li><a href="../public/index.php">الرئيسية</a></li>
                        <li><a href="../public/product.php">المنتجات</a></li>
                        <li><a href="#">التحميلات</a></li>
                        <li><a href="#">الضمان</a></li>
                        <li><a href="#">فريق العمل</a></li>
                        <li><a href="#">تواصل معنا</a></li>
                    </ul>
                </div>
                <div class="quick-social">
                    <div class="em-ph">
                        <span>asunlockhelp@gmail.com</span>
                        <span class="span-phone">+20 106 906 2005</span>
                    </div>
                    <ul class="links">
                        <li><a href="https://www.youtube.com/@as_unlock" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-youtube" viewBox="0 0 16 16"><path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.01 2.01 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.01 2.01 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31 31 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.01 2.01 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A100 100 0 0 1 7.858 2zM6.4 5.209v4.818l4.157-2.408z"/></svg></a></li>
                        <li><a href="https://www.youtube.com/@as_unlock" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16"><path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/></svg></a></li>
                        <li><a href="https://t.me/as_unlock" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telegram" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.287 5.906q-1.168.486-4.666 2.01-.567.225-.595.442c-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294q.39.01.868-.32 3.269-2.206 3.374-2.23c.05-.012.12-.026.166.016s.042.12.037.141c-.03.129-1.227 1.241-1.846 1.817-.193.18-.33.307-.358.336a8 8 0 0 1-.188.186c-.38.366-.664.64.015 1.088.327.216.589.393.85.571.284.194.568.387.936.629q.14.092.27.187c.331.236.63.448.997.414.214-.02.435-.22.547-.82.265-1.417.786-4.486.906-5.751a1.4 1.4 0 0 0-.013-.315.34.34 0 0 0-.114-.217.53.53 0 0 0-.31-.093c-.3.005-.763.166-2.984 1.09"/></svg></a></li>
                        <li><a href="https://wa.me/201069062005?text=مرحبًا%20اريد%20الاستفسار%20عن" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16"><path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/></svg></a></li>
                    </ul>
                </div>
                <div class="footer-content">
                    <p>© <span id="year"></span> جميع الحقوق محفوظة <span>تم انشاء من قبل <a href="https://mostafamtaha.ct.ws" target="_blank">mostafamtaha</a></span></p>
                </div>
            </div>
        </footer>
    </main>
    <script src="../assets/js/download.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const section = document.querySelector('.zip-files-size');
        
        // تحديد جميع العناصر التي نريد إضافة التأثير لها
        const elementsToAnimate = [
            section.querySelector('h1'),
            section.querySelector('.img-file'),
            section.querySelector('.link-download h1'),
            section.querySelector('.link-down')
        ].filter(el => el !== null); // تأكد من وجود العناصر
        
        // إضافة كلاس fade-in-element لكل عنصر
        elementsToAnimate.forEach(el => el.classList.add('fade-in-element'));
        
        // إنشاء Intersection Observer
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // عند ظهور القسم في الشاشة، نضيف التأثير لكل عنصر بالتتابع
                    elementsToAnimate.forEach((el, index) => {
                        setTimeout(() => {
                            el.classList.add('visible');
                        }, 200 * index);
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, {threshold: 0.1}); // عندما يكون 10% من القسم ظاهراً
        
        // بدء مراقبة القسم
        observer.observe(section);
    });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const contactSection = document.querySelector('.contact-us-team');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // عرض رسالة المستخدم أولاً
                    const userMessage = entry.target.querySelector('.massage-user');
                    userMessage.classList.add('show-message');
                    
                    setTimeout(() => {
                        const teamMessage = entry.target.querySelector('.massage-team');
                        teamMessage.classList.add('show-message');
                    }, 700);
                    
                    observer.unobserve(entry.target);
                }
            });
        }, {threshold: 0.1});

        observer.observe(contactSection);
    });
    </script>
</body>
</html>