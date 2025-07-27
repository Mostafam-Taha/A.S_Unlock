<?php
require_once '../includes/config.php';

session_start();

if (empty($_SESSION['admin_id'])) {
    header('Location: logs.php');
    exit;
}
?>

<!DOCTYPE html>
<html dir="rtl" lang="ar">

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
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/review-costm.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">

    <title>Dashboard - Team_administrator</title>
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
                    <li><a href="bouquets.php">الباقات</a></li>
                    <li><a href="products.php">المنتجات</a></li>
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
        <!-- #Customer opinions -->
        <section class="product">
            <div class="contanier">
                <h3 style="margin-bottom: 10px;">إدارة الفريق</h3>
                <div class="detail-order">
                    <div class="function-top-flex">
                        <div class="flex-top">
                            <div class="function-flex-right" style="position: relative;">
                                <!-- <button id="exportBtn" class="btn-export"><i class="bi bi-download"></i> تصدير البيانات</button> -->
                                <button class="btn-plus bi bi-plus-lg plus-add" data-bs-toggle="modal" data-bs-target="#addPersonModal"></button>
                            </div>
                            <div class="function-flex-left">
                                <label for="search-table-product">
                                    <i class="bi bi-search"></i>
                                    <input type="search" name="search-table-product" id="search-table-product" placeholder="بحث عن المنتج...">
                                </label>
                            </div>
                        </div>
                        <div class="prod-table">
                            <table id="itemsTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>الاسم</th>
                                        <th>الوصف</th>
                                        <th>التاريخ</th>
                                        <th>مميز</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    require_once '../includes/config.php';
                                    
                                    try {
                                        $stmt = $pdo->query("SELECT * FROM persons ORDER BY created_at DESC");
                                        $persons = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        
                                        if (count($persons) > 0) {
                                            foreach ($persons as $person) {
                                                echo "<tr>
                                                        <td>{$person['id']}</td>
                                                        <td>{$person['name']}</td>
                                                        <td>{$person['description']}</td>
                                                        <td>{$person['created_at']}</td>
                                                        <td>{$person['type']}</td>
                                                        <td> 
                                                            <a href='#' class='view-item' data-id='{$person['id']}'>عرض</a> | 
                                                            <a href='#' class='delete-item' data-id='{$person['id']}'>حذف</a>
                                                        </td>
                                                    </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='6'>لا توجد بيانات متاحة</td></tr>";
                                        }
                                    } catch (PDOException $e) {
                                        echo "<tr><td colspan='6'>خطأ في جلب البيانات: " . $e->getMessage() . "</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- Win -->
    <!-- نافذة Bootstrap Modal -->
    <div class="modal fade" id="addPersonModal" tabindex="-1" aria-labelledby="addPersonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="addPersonModalLabel">إضافة شخص جديد</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="addPersonForm">
            <!-- صورة المستخدم -->
            <div class="mb-3">
                <label for="userImage" class="form-label">صورة المستخدم</label>
                <input type="file" class="form-control" id="userImage" name="userImage" accept="image/*">
            </div>
            
            <!-- نوع الشخص -->
            <div class="mb-3">
                <label for="personType" class="form-label">النوع</label>
                <select class="form-select" id="personType" name="personType" required>
                <option value="">اختر النوع</option>
                <option value="ادارة">ادارة</option>
                <option value="العمل المساعد">العمل المساعد</option>
                </select>
            </div>
            
            <!-- اسم الشخص -->
            <div class="mb-3">
                <label for="personName" class="form-label">اسم الشخص</label>
                <input type="text" class="form-control" id="personName" name="personName" required>
            </div>
            
            <!-- الوظيفة -->
            <div class="mb-3">
                <label for="personJob" class="form-label">الوظيفة</label>
                <input type="text" class="form-control" id="personJob" name="personJob" required>
            </div>
            
            <!-- الوصف -->
            <div class="mb-3">
                <label for="personDescription" class="form-label">الوصف</label>
                <textarea class="form-control" id="personDescription" name="personDescription" rows="3"></textarea>
            </div>
            
            <!-- المهارات -->
            <div class="mb-3">
                <label class="form-label">المهارات</label>
                <div id="skillsContainer">
                <div class="input-group mb-2">
                    <input type="text" class="form-control skill-input" name="skills[]" placeholder="أدخل المهارة">
                    <button type="button" class="btn btn-outline-primary add-skill-btn">
                    <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
                </div>
            </div>
            
            <!-- وسائل التواصل -->
            <div class="mb-3">
                <label class="form-label">وسائل التواصل</label>
                <div id="socialLinksContainer">
                <div class="row mb-2">
                    <div class="col-md-6">
                    <input type="text" class="form-control" name="socialIcons[]" placeholder="أيقونة (مثال: bi bi-facebook)">
                    </div>
                    <div class="col-md-6">
                    <input type="url" class="form-control" name="socialLinks[]" placeholder="رابط وسيلة التواصل">
                    </div>
                </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="addSocialLinkBtn">
                <i class="bi bi-plus-lg"></i> إضافة رابط آخر
                </button>
            </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            <button type="button" class="btn btn-primary" id="savePersonBtn">حفظ</button>
        </div>
        </div>
    </div>
    </div>
    <!-- نافذة عرض وتعديل بيانات المستخدم -->
<div class="modal fade" id="personDetailsModal" tabindex="-1" aria-labelledby="personDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="personDetailsModalLabel">تفاصيل المستخدم</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <form id="editPersonForm">
                    <input type="hidden" id="editPersonId" name="editPersonId">
                    
                    <!-- صورة المستخدم -->
                    <div class="mb-3">
                        <label for="editUserImage" class="form-label">صورة المستخدم</label>
                        <div class="d-flex align-items-center">
                            <img id="editUserImagePreview" src="../uploads/default_avatar.jpg" class="img-thumbnail me-3" style="width: 100px; height: 100px; object-fit: cover;" loading="lazy">
                            <input type="file" class="form-control" id="editUserImage" name="editUserImage" accept="image/*">
                        </div>
                    </div>
                    
                    <!-- نوع الشخص -->
                    <div class="mb-3">
                        <label for="editPersonType" class="form-label">النوع</label>
                        <select class="form-select" id="editPersonType" name="editPersonType" required>
                            <option value="">اختر النوع</option>
                            <option value="ادارة">ادارة</option>
                            <option value="العمل المساعد">العمل المساعد</option>
                        </select>
                    </div>
                    
                    <!-- اسم الشخص -->
                    <div class="mb-3">
                        <label for="editPersonName" class="form-label">اسم الشخص</label>
                        <input type="text" class="form-control" id="editPersonName" name="editPersonName" required>
                    </div>
                    
                    <!-- الوظيفة -->
                    <div class="mb-3">
                        <label for="editPersonJob" class="form-label">الوظيفة</label>
                        <input type="text" class="form-control" id="editPersonJob" name="editPersonJob" required>
                    </div>
                    
                    <!-- الوصف -->
                    <div class="mb-3">
                        <label for="editPersonDescription" class="form-label">الوصف</label>
                        <textarea class="form-control" id="editPersonDescription" name="editPersonDescription" rows="3"></textarea>
                    </div>
                    
                    <!-- المهارات -->
                    <div class="mb-3">
                        <label class="form-label">المهارات</label>
                        <div id="editSkillsContainer">
                            <!-- سيتم إضافة حقول المهارات ديناميكيًا هنا -->
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="editAddSkillBtn">
                            <i class="bi bi-plus-lg"></i> إضافة مهارة جديدة
                        </button>
                    </div>
                    
                    <!-- وسائل التواصل -->
                    <div class="mb-3">
                        <label class="form-label">وسائل التواصل</label>
                        <div id="editSocialLinksContainer">
                            <!-- سيتم إضافة حقول وسائل التواصل ديناميكيًا هنا -->
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="editAddSocialLinkBtn">
                            <i class="bi bi-plus-lg"></i> إضافة رابط آخر
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" id="updatePersonBtn">تحديث البيانات</button>
            </div>
        </div>
    </div>
</div>
    <div class="screen-size-warning">
        ⚠️ عذراً، الموقع لا يعمل بشكل صحيح على شاشات أصغر من 600px<br>
        الرجاء استخدام جهاز بشاشة أكبر أو تكبير نافذة المتصفح
    </div>
    <!--  -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/team_administrator.js"></script>
    <script src="../assets/js/dark-mode.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.querySelector('.toggle-menu');
            const header = document.querySelector('.header');
            const mainContent = document.querySelector('.main-content');

            const isHeaderClosed = localStorage.getItem('headerClosed') === 'true';

            if (isHeaderClosed) {
                header.classList.add('closed');
            } else {
                header.classList.remove('closed');
            }

            toggleBtn.addEventListener('click', function () {
                header.classList.toggle('closed');

                // حفظ الحالة في localStorage
                localStorage.setItem('headerClosed', header.classList.contains('closed'));
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const profileImage = document.getElementById('profileImage');
            const menuProfile = document.querySelector('.menu-profile');

            profileImage.addEventListener('click', function (e) {
                e.stopPropagation();
                menuProfile.classList.toggle('active');
            });

            document.addEventListener('click', function (e) {
                if (!menuProfile.contains(e.target) && e.target !== profileImage) {
                    menuProfile.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>