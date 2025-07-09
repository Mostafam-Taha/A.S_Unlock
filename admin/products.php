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
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">

    <title>Dashboard - Products</title>
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
                    <li><a href="#">نظرة عامة</a></li>
                    <li><a href="#">التحاليل</a></li>
                    <li><a href="#">أدارة مالية</a></li>
                </ul>
            </div>
            <div class="un-order-list-app">
                <span class="un-title">التطبيقات</span>
                <ul class="app">
                    <li><a href="orders.php">الطلبات</a></li>
                    <li><a href="#">الفاتورة</a></li>
                    <li><a href="#">الاشتراكات</a></li>
                </ul>
            </div>
            <div class="un-order-list-page">
                <span class="un-title">الصفحات</span>
                <ul class="page">
                    <li><a href="users.php">المستخدمين</a></li>
                    <li><a href="#">الموظفين</a></li>
                    <li><a href="#">الخدمات</a></li>
                    <li><a href="#">المنتجات</a></li>
                    <li><a href="#">اراء العملاء</a></li>
                    <li><a href="#">اضافة وظيفة جديدة</a></li>
                    <li><a href="download.php">تحميلات</a></li>
                    <li><a href="#">الضمان</a></li>
                    <li><a href="#">الأسئلة الشائعة</a></li>
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
        <!-- #Product -->
        <section class="product">
            <div class="contanier">
                <h3>الطلبات</h3>
                <div class="detail-order">
                    <div class="function-top-flex">
                        <div class="flex-top">
                            <div class="function-flex-right">
                                <button class="btn-plus" id="openProductModal" class="custom-btn"><i class="bi bi-plus"></i> اضافة طلب</button>
                                <!-- <button><i class="bi bi-download"></i> تصدير</button> -->
                                <button onclick="exportToPDF()"><i class="bi bi-download"></i> تصدير</button>
                            </div>
                            <div class="function-flex-left">
                                <label for="search-table-product">
                                    <i class="bi bi-search"></i>
                                    <input type="search" name="search-table-product" id="search-table-product" placeholder="بحث عن المنتج..." oninput="searchProducts()">
                                </label>
                            </div>
                        </div>
                        <?php
                        require_once '../includes/config.php';

                        // استعلام لاسترجاع البيانات
                        try {
                            $stmt = $pdo->query("
                                SELECT 
                                    id,
                                    product_name,
                                    price,
                                    discount,
                                    features,
                                    is_published,
                                    created_at
                                FROM digital_products
                                ORDER BY created_at DESC
                            ");
                            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        } catch (PDOException $e) {
                            die("خطأ في استرجاع البيانات: " . $e->getMessage());
                        }
                        ?>

                        <div class="prod-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>أسم العنصر</th>
                                        <th>سعر</th>
                                        <th>عدد المميزات</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td>
                                            <?= htmlspecialchars($product['product_name']) ?>
                                        </td>
                                        <td>
                                            <?= number_format($product['price'], 2) ?> ج
                                            <?php if ($product['discount']): ?>
                                            <span class="discount-badge">خصم
                                                <?= number_format($product['discount'], 2) ?> ج
                                            </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php 
                                                $features = json_decode($product['features'], true);
                                                echo $features ? count($features) : 0;
                                                ?>
                                        </td>
                                        <td>
                                            <span
                                                class="status-badge <?= $product['is_published'] ? 'published' : 'unpublished' ?>">
                                                <?= $product['is_published'] ? 'منشور' : 'معطل' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="view-btn" onclick="showProductDetails(<?= $product['id'] ?>)">عرض</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Window Add Product -->
    <div id="addProductModal" class="custom-modal">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h3>إضافة منتج رقمي جديد</h3>
                <button id="closeModal" class="custom-close-btn">&times;</button>
            </div>
            <div class="custom-modal-body">
                <form id="productForm" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="productImage">صورة المنتج</label>
                            <input type="file" id="productImage" name="productImage" accept="image/*"
                                class="custom-file-input">
                            <div class="image-preview-container">
                                <img id="imagePreview" src="#" alt="Preview">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="productName">اسم المنتج</label>
                            <input type="text" id="productName" name="productName" required class="custom-input">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="productDescription">وصف المنتج</label>
                        <textarea id="productDescription" name="productDescription" rows="3" required
                            class="custom-textarea"></textarea>
                    </div>

                    <div class="form-group">
                        <label>مميزات المنتج</label>
                        <div id="featuresContainer" class="features-container">
                            <div class="feature-item">
                                <button type="button" class="add-feature-btn"><i class="bi bi-plus"></i></button>
                                <input type="text" name="features[]" placeholder="ادخل الميزة" class="feature-input">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="productPrice">سعر المنتج</label>
                            <input type="number" id="productPrice" name="productPrice" step="0.01" required
                                class="custom-input">
                        </div>

                        <div class="form-group">
                            <label for="productDiscount">خصم المنتج (اختياري)</label>
                            <input type="number" id="productDiscount" name="productDiscount" step="0.01"
                                class="custom-input">
                        </div>
                    </div>

                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="isFeatured" name="isFeatured" class="custom-checkbox">
                        <label for="isFeatured">منتج مميز</label>
                    </div>

                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="isPublished" name="isPublished" checked class="custom-checkbox">
                        <label for="isPublished">تفعيل النشر</label>
                    </div>
                </form>
            </div>
            <div class="custom-modal-footer">
                <button id="cancelBtn" class="custom-btn secondary">إلغاء</button>
                <button id="saveProduct" class="custom-btn primary">حفظ المنتج</button>
            </div>
        </div>
    </div>
    <!--  -->
    <!--  -->
    <div class="product-management-system">
        <div id="productDetailsModal" class="details-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>تفاصيل المنتج</h3>
                    <button class="close-btn" onclick="closeDetailsModal()">&times;</button>
                </div>
                <div class="modal-body" id="productDetailsContent">
                    <!-- سيتم ملؤه بالجافاسكريبت -->
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
    <script src="../assets/js/dashboard.js"></script>
    <script src="../assets/js/exportToPDF.js"></script>
    <script src="../assets/js/code-dashboard.js"></script>
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