<?php
require_once '../includes/config.php';
require_once '../includes/check_maintenance.php';

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

<!-- لعرض اسم المستخدم تم التعديل 14/7 -->
<?php

    try {
        // استعلام لاسترجاع بيانات المستخدم (مثال: المستخدم الأول)
        $stmt = $pdo->query("SELECT name FROM users LIMIT 1");
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // إذا وجدنا المستخدم
        if ($user) {
            $userName = htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8');
        } else {
            $userName = "لم يتم العثور على عميل";
        }
    } catch (PDOException $e) {
        $userName = "خطأ في استرجاع البيانات";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap"rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/download.css">
    <link rel="stylesheet" href="../assets/css/dark-mode-index.css">

    <title>A.S UNLOCK</title>
</head>
<body class="dark-mode-index">
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
                <li><a href="guarantees.php">الضمان</a></li>
                <li><a href="../team/team.php">فريق العمل</a></li>
            </ul>
            <ul class="btn">
                <li><a href="https://wa.me/201069062005?text=السلام%20عليكم%20ورحمة%20الله%20وبركاته%0Aعاوز%20استفسر%20عن%20" class="contact co-btn" target="_blank">أتصل بنا <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z" /></svg></a></li>
                <li class="dark-mode-toggle"><a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-circle-half" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 0 8 1zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16" /></svg></a></li>
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
                        <div class="download-file-ultar"><a href="https://www.ultraviewer.net/en/UltraViewer_setup_6.6_en.exe">تحميل</a></div>
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
                                                <div><a href="downloadfile.php?id=<?php echo $file['id']; ?>" title="تحميل"><i class="bi bi-download"></i></a></div>
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
                            <div class="name"><?php echo $userName; ?></div>
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
                        <li><a href="guarantees.php">الضمان</a></li>
                        <li><a href="../team/team.php">فريق العمل</a></li>
                        <li><a href="https://wa.me/201069062005?text=السلام%20عليكم%20ورحمة%20الله%20وبركاته%0Aعاوز%20استفسر%20عن%20">تواصل معنا</a></li>
                    </ul>
                </div>
                <div class="quick-social">
                    <div class="em-ph">
                        <span>asunlockhelp@gmail.com</span>
                        <span class="span-phone">+20 106 906 2005</span>
                    </div>
                    <ul class="links social-links">
                        <!--  -->
                        <!-- === -->
                        <!--  -->
                    </ul>
                </div>
                <div class="footer-content">
                    <p>© <span id="year"></span> جميع الحقوق محفوظة <span>تم انشاء من قبل <a href="https://mostafamtaha.ct.ws" target="_blank">mostafamtaha</a></span></p>
                </div>
            </div>
        </footer>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/download.js"></script>
    <script src="../assets/js/public_links.js"></script>
    <script src="../assets/js/dark-mode.js"></script>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburger = document.getElementById('hamburger');
            const navbar = document.getElementById('navbar');
            
            hamburger.addEventListener('click', function() {
                this.classList.toggle('active');
                navbar.classList.toggle('active');
                
                // منع التمرير عند فتح القائمة
                if (navbar.classList.contains('active')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = 'auto';
                }
            });
            
            // إغلاق القائمة عند النقر على رابط
            const navLinks = document.querySelectorAll('.navbar a');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    hamburger.classList.remove('active');
                    navbar.classList.remove('active');
                    document.body.style.overflow = 'auto';
                });
            });
        });
    </script>
</body>
</html>