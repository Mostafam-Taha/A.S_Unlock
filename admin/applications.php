<?php
require_once '../includes/config.php';
// require_once 'auth.php';

try {
    $stmt = $pdo->query("SELECT id, full_name, phone_number, work_type, status, submission_date FROM job_applications ORDER BY submission_date DESC");
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching applications: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="YOUR_CSRF_TOKEN_VALUE">
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
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
    
    <title>Applications</title>
    <style>
        ol, ul {padding-right: 0;}
        .btn-btn-sm-setork {display: none;}
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header" id="mainHeader">
        <div class="logo"><img src="../assets/image/favicon.ico" alt="Not Found Logo" loading="lazy">
            <h1>A.S_UNLOCK</h1>
        </div>
        <nav class="navber">
            <div class="un-order-list-sect">
                <span class="un-title">التقارير</span>
                <ul class="sect">
                    <li><a href="dashboard.php">نظرة عامة</a></li>
                    <li><a href="crypto.php">أدارة مالية</a></li>
                </ul>
            </div>
            <div class="un-order-list-app">
                <span class="un-title">التطبيقات</span>
                <ul class="app">
                    <li><a href="orders.php">الطلبات</a></li>
                    <li><a href="add_links.php">أدارة اللينكات</a></li>
                </ul>
            </div>
            <div class="un-order-list-page">
                <span class="un-title">الصفحات</span>
                <ul class="page">
                    <li><a href="./users.php">المستخدمين</a></li>
                    <li><a href="team_administrator.php">الموظفين</a></li>
                    <li><a href="add_team.php">ادارة الفريق index</a></li>
                    <li><a href="applications.php">اضافة وظيفة</a></li>
                    <li><a href="bouquets.php">الباقات</a></li>
                    <li><a href="#">المنتجات</a></li>
                    <li><a href="review-costm.php">اراء العملاء</a></li>
                    <li><a href="download.php">تحميلات</a></li>
                    <li><a href="warranty.php">الضمان</a></li>
                    <li><a href="common-questions.php">الأسئلة الشائعة</a></li>
                </ul>
            </div>
            <div class="un-order-list-spp">
                <span class="un-title">الدعم</span>
                <ul class="spp">
                    <li><a href="#">التواصل مع الدعم</a></li>
                    <li><a href="#">ارشادات عمل A.S...</a></li>
                    <li><a href="#">سياسة mostafamtaha</a></li>
                    <li><a href="#">خدمة مع بعد البيع</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Body Dashboard -->
    <!-- Header - Top Short Cut -->
    <main class="main-content">
        <div class="header-top">
            <div class="ht-sei">
                <div class="flex-pro-net">
                    <div class="logo-profile"><img src="../assets/image/favicon.ico" alt="Not Image Profile" loading="lazy" id="profileImage"></div>
                    <div class="menu-profile">
                        <ul>
                            <li><a href="#">الملف الشخصي <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" /></svg></a></li>
                            <li><a href="#">تحليل<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-graph-up" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M0 0h1v15h15v1H0zm14.817 3.113a.5.5 0 0 1 .07.704l-4.5 5.5a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61 4.15-5.073a.5.5 0 0 1 .704-.07" /></svg></a></li>
                        </ul>
                        <hr>
                        <ul>
                            <li><a href="./logout.php">تسجيل الخروج</a></li>
                        </ul>
                    </div>
                    <ul class="qu">
                        <li class="dark-mode-toggle"><a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-circle-half" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 0 8 1zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16" /></svg></a></li>
                        <li><a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16"><path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901" /></svg></a></li>
                    </ul>
                </div>
                <div class="flex-se">
                    <!-- <div class="for-search">
                        <label for="search">
                            <input type="search" name="search" id="search" placeholder="بحث ...">
                        </label>
                    </div> -->
                    <ul>
                        <li>
                            <button class="toggle-menu" aria-label="Toggle Menu">
                                <i class="bi bi-text-indent-left"></i>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Dashboard Body -->
        <!-- Section Body -->
        <!-- #applications -->
        <section class="product">
            <div class="contanier">
                <h3>الوظائف</h3>
                <div class="detail-order" style="border-radius: 15px; margin-top: 25px;">
                    <div class="function-top-flex">
                        <div class="flex-top">
                            <div class="function-flex-right">
                                <button class="btn btn-primary custom-btn" data-bs-toggle="modal" data-bs-target="#addJobModal">
                                <i class="bi bi-plus"></i> إضافة وظيفة جديدة
                                </button>
                            </div>
                            <div class="function-flex-left">
                                <label for="search-table-product">
                                    <i class="bi bi-search"></i>
                                    <input type="search" name="search-table-product" id="search-table-product" placeholder="بحث عن المنتج..." oninput="searchProducts()">
                                </label>
                            </div>
                        </div>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الاسم</th>
                                        <th>الهاتف</th>
                                        <th>نوع العمل</th>
                                        <th>التاريخ</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($applications as $app): ?>
                                    <tr>
                                        <td><?= $app['id'] ?></td>
                                        <td><?= htmlspecialchars($app['full_name']) ?></td>
                                        <td><?= $app['phone_number'] ?></td>
                                        <td><?= $app['work_type'] === 'online' ? 'عن بعد' : 'في الموقع' ?></td>
                                        <td><?= date('Y-m-d', strtotime($app['submission_date'])) ?></td>
                                        <td>
                                            <span class="status-badge status-<?= $app['status'] ?>">
                                                <?php 
                                                    switch($app['status']) {
                                                        case 'accepted': echo 'مقبول'; break;
                                                        case 'rejected': echo 'مرفوض'; break;
                                                        default: echo 'قيد المراجعة';
                                                    }
                                                ?>
                                            </span>
                                        </td>
                                        <td class="action-btns">
                                            <button class="btn btn-sm btn-success accept-btn" 
                                                    data-id="<?= $app['id'] ?>" 
                                                    data-phone="<?= $app['phone_number'] ?>"
                                                    data-name="<?= htmlspecialchars($app['full_name']) ?>">
                                                <i class="fas fa-check"></i> قبول
                                            </button>
                                            
                                            <button class="btn btn-sm btn-danger reject-btn" 
                                                    data-id="<?= $app['id'] ?>" 
                                                    data-phone="<?= $app['phone_number'] ?>"
                                                    data-name="<?= htmlspecialchars($app['full_name']) ?>">
                                                <i class="fas fa-times"></i> رفض
                                            </button>
                                            
                                            <button class="btn btn-sm btn-info view-btn" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#detailsModal"
                                                    data-id="<?= $app['id'] ?>">
                                                <i class="fas fa-eye"></i> تفاصيل
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card menu-jobs" style="border-radius: 15px; margin-top: 25px; padding: 15px;">
                        <div>
                            <h5 class="card-title mb-0">قائمة الوظائف</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="jobsTable">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="25%">عنوان الوظيفة</th>
                                            <th width="15%">التصنيف</th>
                                            <th width="10%">الحالة</th>
                                            <th width="15%">تاريخ النشر</th>
                                            <th width="15%">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- Window -->
    <div class="modal fade" id="addJobModal" tabindex="-1" aria-labelledby="addJobModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-white">
                    <h5 class="modal-title" id="addJobModalLabel">إضافة وظيفة جديدة</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="jobForm" novalidate>
                        <div class="mb-4">
                            <h5 class="mb-3 border-bottom pb-2">المعلومات الأساسية <small class="text-danger">* حقول مطلوبة</small></h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="jobTitle" class="form-label">عنوان الوظيفة <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="jobTitle" name="jobTitle" required>
                                    <div class="invalid-feedback">يرجى إدخال عنوان الوظيفة</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="jobCategory" class="form-label">التصنيف <span class="text-danger">*</span></label>
                                    <select class="form-select" id="jobCategory" name="jobCategory" required>
                                        <option value="" selected disabled>اختر التصنيف</option>
                                        <option value="تطوير الويب">تطوير الويب</option>
                                        <option value="تصميم جرافيك">تصميم جرافيك</option>
                                        <option value="تسويق إلكتروني">تسويق إلكتروني</option>
                                        <option value="عمل جماعي">عمل جماعي</option>
                                        <option value="أخرى">أخرى</option>
                                    </select>
                                    <div class="invalid-feedback">يرجى اختيار تصنيف الوظيفة</div>
                                </div>
                                <div class="col-12">
                                    <label for="jobDescription" class="form-label">وصف الوظيفة <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="jobDescription" name="jobDescription" rows="3" required></textarea>
                                    <div class="invalid-feedback">يرجى إدخال وصف الوظيفة</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="jobPublished">
                                        <label class="form-check-label" for="jobPublished">نشر الوظيفة الآن</label>
                                    </div>
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" name="showPopup" value="1" checked>
                                            عرض النافذة المنبثقة للوظيفة
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="mb-3 border-bottom pb-2">متطلبات الوظيفة <small class="text-muted">(اختيارية)</small></h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="jobExperience" class="form-label">الخبرة المطلوبة</label>
                                    <select class="form-select" id="jobExperience">
                                        <option value="" selected>اختياري</option>
                                        <option value="لا يشترط خبرة">لا يشترط خبرة</option>
                                        <option value="مبتدئ (أقل من سنة)">مبتدئ (أقل من سنة)</option>
                                        <option value="1-3 سنوات">1-3 سنوات</option>
                                        <option value="3-5 سنوات">3-5 سنوات</option>
                                        <option value="أكثر من 5 سنوات">أكثر من 5 سنوات</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="jobSkills" class="form-label">المهارات المطلوبة</label>
                                    <textarea class="form-control" id="jobSkills" rows="2" placeholder="أدخل المهارات مفصولة بفواصل"></textarea>
                                    <small class="text-muted">مثال: HTML, CSS, JavaScript, PHP</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="mb-3 border-bottom pb-2">تفاصيل إضافية <small class="text-muted">(اختيارية)</small></h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="jobType" class="form-label">نوع الوظيفة</label>
                                    <select class="form-select" id="jobType">
                                        <option value="" selected>اختياري</option>
                                        <option value="دوام كامل">دوام كامل</option>
                                        <option value="دوام جزئي">دوام جزئي</option>
                                        <option value="عن بعد">عن بعد</option>
                                        <option value="مشروع مؤقت">مشروع مؤقت</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="jobSalary" class="form-label">الراتب</label>
                                    <input type="text" class="form-control" id="jobSalary" placeholder="مثال: 5000-7000 ريال">
                                </div>
                                <div class="col-md-4">
                                    <label for="jobLocation" class="form-label">الموقع</label>
                                    <input type="text" class="form-control" id="jobLocation" placeholder="مدينة أو عن بعد">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="mb-3 border-bottom pb-2">مزايا الوظيفة <small class="text-muted">(اختيارية)</small></h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="jobBenefits" class="form-label">مزايا إضافية</label>
                                    <textarea class="form-control" id="jobBenefits" rows="2" placeholder="أدخل مزايا الوظيفة"></textarea>
                                    <small class="text-muted">مثال: تأمين صحي، مواصلات، بدل سكن</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="mb-3 border-bottom pb-2">معلومات الشركة <small class="text-muted">(اختيارية)</small></h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="jobCompany" class="form-label">اسم الشركة</label>
                                    <input type="text" class="form-control" id="jobCompany">
                                </div>
                                <div class="col-md-6">
                                    <label for="jobCompanyWebsite" class="form-label">موقع الشركة</label>
                                    <input type="url" class="form-control" id="jobCompanyWebsite" placeholder="https://example.com">
                                </div>
                                <div class="col-md-6">
                                    <label for="jobContactEmail" class="form-label">البريد الإلكتروني للتواصل</label>
                                    <input type="email" class="form-control" id="jobContactEmail" placeholder="hr@example.com">
                                </div>
                                <div class="col-md-6">
                                    <label for="jobDeadline" class="form-label">آخر موعد للتقديم</label>
                                    <input type="date" class="form-control" id="jobDeadline">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h5 class="mb-3 border-bottom pb-2">المرفقات <small class="text-muted">(اختيارية)</small></h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="jobImage" class="form-label">صورة الوظيفة</label>
                                    <input class="form-control" type="file" id="jobImage" accept="image/*">
                                </div>
                                <div class="col-md-6">
                                    <label for="jobAttachments" class="form-label">مرفقات إضافية</label>
                                    <input class="form-control" type="file" id="jobAttachments" multiple>
                                    <small class="text-muted">يمكن رفع أكثر من ملف (PDF, Word, Excel)</small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary" id="submitJobBtn">حفظ الوظيفة</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تفاصيل الطلب</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detailsContent">
                    جاري تحميل البيانات...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal للرسالة -->
    <div class="modal fade" id="messageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalTitle">إرسال رسالة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="messageForm">
                        <input type="hidden" id="applicationId">
                        <input type="hidden" id="applicationPhone">
                        <input type="hidden" id="actionType">
                        
                        <div class="mb-3">
                            <label for="messageText" class="form-label">نص الرسالة</label>
                            <textarea class="form-control" id="messageText" rows="5" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary" id="sendMessageBtn">إرسال</button>
                </div>
            </div>
        </div>
    </div>
    <!--  -->
    <!--  -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!--  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="../assets/js/dark-mode.js"></script>
    <script src="../assets/js/applications.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.querySelector('.toggle-menu');
            const header = document.querySelector('.header');
            const mainContent = document.querySelector('.main-content');

            // تحميل الحالة من localStorage
            const isHeaderClosed = localStorage.getItem('headerClosed') === 'true';

            // تطبيق الحالة المحفوظة
            if (isHeaderClosed) {
                header.classList.add('closed');
            } else {
                header.classList.remove('closed');
            }

            // النقر على زر القائمة
            toggleBtn.addEventListener('click', function () {
                header.classList.toggle('closed');

                // حفظ الحالة في localStorage
                localStorage.setItem('headerClosed', header.classList.contains('closed'));
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const profileImage = document.getElementById('profileImage');
            const menuProfile = document.querySelector('.menu-profile');

            // عند النقر على صورة الملف الشخصي
            profileImage.addEventListener('click', function (e) {
                e.stopPropagation(); // منع انتشار الحدث لتجنب الإغلاق الفوري
                menuProfile.classList.toggle('active');
            });

            // إغلاق القائمة عند النقر في أي مكان خارجها
            document.addEventListener('click', function (e) {
                if (!menuProfile.contains(e.target) && e.target !== profileImage) {
                    menuProfile.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>