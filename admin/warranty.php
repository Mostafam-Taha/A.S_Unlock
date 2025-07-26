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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/warranty.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">

    <title>Warranty</title>
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
                    <li><a href="#">الموظفين</a></li>
                    <li><a href="#">الباقات</a></li>
                    <li><a href="products.php">المنتجات</a></li>
                    <li><a href="review-costm.php">اراء العملاء</a></li>
                    <li><a href="#">اضافة وظيفة جديدة</a></li>
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
                            <li><a href="login_attempts.php">تحليل<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-graph-up" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M0 0h1v15h15v1H0zm14.817 3.113a.5.5 0 0 1 .07.704l-4.5 5.5a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61 4.15-5.073a.5.5 0 0 1 .704-.07" /></svg></a></li>
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
        <!-- #Warranty -->
        <section class="product">
            <div class="contanier">
                <h3 style="margin-bottom: 10px;">الضمان</h3>
                <div class="detail-order">
                    <div class="function-top-flex">
                        <div class="flex-top">
                            <div class="function-flex-right" style="position: relative;">
                                <!-- <button id="exportBtn" class="btn-export"><i class="bi bi-download"></i> تصدير البيانات</button> -->
                                <!-- <button class="btn-plus bi bi-plus-lg plus-add"></button> -->
                            </div>
                            <div class="function-flex-left">
                                <label for="search-table-product">
                                    <i class="bi bi-search"></i>
                                    <input type="search" name="search-table-product" id="search-table-product" placeholder="بحث " aria-label="بحث في الضمان">
                                </label>
                            </div>
                        </div>
                        <div class="prod-table">
                            <table id="itemsTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>الاسم</th>
                                        <th>حالة الضمان</th>
                                        <th>نوع الخدمة</th>
                                        <th>رقم التواصل</th>
                                        <th>مدة الضمان</th>
                                        <th>تاريخ انتهاء الضمان</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                require_once '../includes/config.php';

                                try {
                                    $query = "
                                        SELECT 
                                            o.id AS order_id,
                                            u.name AS user_name,
                                            u.verified AS user_verified,
                                            dp.product_name AS service_type,
                                            o.phone_number AS contact_number,
                                            dp.warranty_duration_days AS warranty_duration,
                                            DATE_ADD(o.created_at, INTERVAL dp.warranty_duration_days DAY) AS warranty_expiry_date
                                        FROM 
                                            orders o
                                        JOIN 
                                            digital_products dp ON o.product_id = dp.id
                                        JOIN 
                                            users u ON o.user_id = u.id
                                        WHERE 
                                            o.status = 'completed'
                                        ORDER BY 
                                            o.created_at DESC
                                    ";
                                    
                                    $stmt = $pdo->query($query);
                                    
                                    // التحقق من عدد الصفوف المسترجعة
                                    $rowCount = $stmt->rowCount();
                                    echo "<!-- عدد الطلبات المكتملة: $rowCount -->";
                                    
                                    if ($rowCount === 0) {
                                        echo "<tr><td colspan='8' style='text-align:center;color:red;'>لا توجد طلبات مكتملة لعرضها</td></tr>";
                                    } else {
                                        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        
                                        foreach ($orders as $order) {
                                            echo "<tr>";
                                            echo "<td>#" . str_pad($order['order_id'], 2, '0', STR_PAD_LEFT) . "</td>";
                                            echo "<td>" . htmlspecialchars($order['user_name']) . "</td>";
                                            echo "<td>" . ($order['user_verified'] ? 'مفعل' : 'غير مفعل') . "</td>";
                                            echo "<td>" . htmlspecialchars($order['service_type']) . "</td>";
                                            echo "<td>" . htmlspecialchars($order['contact_number']) . "</td>";
                                            echo "<td>" . $order['warranty_duration'] . " يوم</td>";
                                            echo "<td>" . date('d-m-Y', strtotime($order['warranty_expiry_date'])) . "</td>";
                                            echo "<td><a href='javascript:void(0);' class='view-item whatsapp-btn bi bi-whatsapp' data-phone='" . htmlspecialchars($order['contact_number']) . "' data-order='" . htmlspecialchars(json_encode($order), ENT_QUOTES, 'UTF-8') . "'></a></td>";
                                            echo "</tr>";
                                        }
                                    }
                                } catch (PDOException $e) {
                                    echo "<!-- خطأ في الاستعلام: " . $e->getMessage() . " -->";
                                    echo "<tr><td colspan='8' style='text-align:center;color:red;'>حدث خطأ في جلب البيانات. الرجاء التحقق من سجلات الخطأ.</td></tr>";
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
    <div class="screen-size-warning">
        ⚠️ عذراً، الموقع لا يعمل بشكل صحيح على شاشات أصغر من 600px<br>
        الرجاء استخدام جهاز بشاشة أكبر أو تكبير نافذة المتصفح
    </div>
    <!--  -->
    <script src="../assets/js/warranty.js"></script>
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













        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-table-product');
            const table = document.getElementById('itemsTable');
            const rows = table.getElementsByTagName('tr');
            
            // دالة البحث
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.trim().toLowerCase();
                const searchColumns = [1, 3, 4]; // أعمدة الاسم (1)، نوع الخدمة (3)، رقم الهاتف (4)
                
                for (let i = 1; i < rows.length; i++) { // ابدأ من 1 لتخطي رأس الجدول
                    const cells = rows[i].getElementsByTagName('td');
                    let rowMatches = false;
                    
                    // ابحث في الأعمدة المحددة
                    for (let j = 0; j < searchColumns.length; j++) {
                        const colIndex = searchColumns[j];
                        if (cells[colIndex]) {
                            const cellText = cells[colIndex].textContent.toLowerCase();
                            if (cellText.includes(searchTerm)) {
                                rowMatches = true;
                                break;
                            }
                        }
                    }
                    
                    // عرض/إخفاء الصف حسب نتيجة البحث
                    rows[i].style.display = rowMatches ? '' : 'none';
                }
            });
        });
    </script>
</body>
</html>