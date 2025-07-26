<?php
require_once '../includes/config.php';

session_start();

if (empty($_SESSION['admin_id'])) {
    header('Location: logs.php');
    exit;
}
?>


<?php
    require_once('../includes/config.php');

    $users_query = "SELECT 
        COUNT(*) AS total_users,
        SUM(google_id IS NOT NULL) AS google_users,
        SUM(google_id IS NULL) AS email_users,
        SUM(verified = 0) AS unverified_users,
        SUM(banned = 1) AS banned_users
    FROM users";

    $uploads_query = "SELECT 
        SUM(IF(file_name IS NOT NULL, 1, 0)) AS file_uploads,
        SUM(IF(link_url IS NOT NULL, 1, 0)) AS link_uploads,
        SUM(file_size) AS total_file_size,
        AVG(file_size) AS avg_file_size,
        COUNT(*) AS total_uploads
    FROM uploads";

    $plans_query = "SELECT 
        plan_type,
        COUNT(*) AS plan_count,
        SUM(plan_price) AS total_revenue
    FROM product_plans
    GROUP BY plan_type";

    $orders_query = "SELECT 
        status,
        COUNT(*) AS order_count,
        SUM(amount) AS total_amount
    FROM orders
    GROUP BY status";

    $users_stats = $pdo->query($users_query)->fetch(PDO::FETCH_ASSOC);
    $uploads_stats = $pdo->query($uploads_query)->fetch(PDO::FETCH_ASSOC);
    $plans_stats = $pdo->query($plans_query)->fetchAll(PDO::FETCH_ASSOC);
    $orders_stats = $pdo->query($orders_query)->fetchAll(PDO::FETCH_ASSOC);

    $plan_types = [];
    $plan_counts = [];
    $plan_revenues = [];

    foreach ($plans_stats as $plan) {
        $plan_types[] = $plan['plan_type'];
        $plan_counts[] = $plan['plan_count'];
        $plan_revenues[] = $plan['total_revenue'];
    }

    $order_statuses = [];
    $order_counts = [];
    $order_amounts = [];

    foreach ($orders_stats as $order) {
        $order_statuses[] = $order['status'];
        $order_counts[] = $order['order_count'];
        $order_amounts[] = $order['total_amount'];
    }

    $last_users_query = "SELECT id, name, email, created_at, verified, banned 
                        FROM users 
                        ORDER BY created_at DESC 
                        LIMIT 5";
    $last_users = $pdo->query($last_users_query)->fetchAll(PDO::FETCH_ASSOC);

    $last_orders_query = "SELECT o.id, u.name AS user_name, p.plan_name, o.amount, o.status, o.created_at 
                        FROM orders o
                        JOIN users u ON o.user_id = u.id
                        JOIN product_plans p ON o.plan_id = p.id
                        ORDER BY o.created_at DESC 
                        LIMIT 5";
    $last_orders = $pdo->query($last_orders_query)->fetchAll(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="../assets/css/chart.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">

    <title>Overview</title>
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
        <!-- #Analytics -->
        <div class="container-fluid">
            <div class="dashboard-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1><i style="margin-left: 15px" class="fas fa-chart-line me-3"></i>التحاليل</h1>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="stat-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="text-primary text-uppercase fw-bold mb-1">
                                        المستخدمون
                                    </div>
                                    <div class="h2 mb-0 fw-bold"><?php echo number_format($users_stats['total_users']); ?></div>
                                    <div class="mt-2 small">
                                        <span class="text-success fw-bold">
                                            <i class="fas fa-users me-1"></i>
                                            <?php echo number_format($users_stats['email_users']); ?> عبر البريد
                                        </span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="stat-card success">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="text-success text-uppercase fw-bold mb-1">
                                        التحميلات
                                    </div>
                                    <div class="h2 mb-0 fw-bold"><?php echo number_format($uploads_stats['total_uploads']); ?></div>
                                    <div class="mt-2 small">
                                        <span class="text-muted">
                                            <?php echo number_format($uploads_stats['file_uploads']); ?> ملفات
                                        </span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-file-upload fa-2x text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="stat-card info">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="text-info text-uppercase fw-bold mb-1">
                                        الطلبات
                                    </div>
                                    <div class="h2 mb-0 fw-bold"><?php echo number_format(array_sum($order_counts)); ?></div>
                                    <div class="mt-2 small">
                                        <span class="text-success fw-bold">
                                            <i class="fas fa-check-circle me-1"></i>
                                            <?php 
                                                $completed = 0;
                                                foreach($orders_stats as $os) {
                                                    if($os['status'] == 'completed') $completed = $os['order_count'];
                                                }
                                                echo number_format($completed);
                                            ?> مكتملة
                                        </span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-shopping-cart fa-2x text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="stat-card warning">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <div class="text-warning text-uppercase fw-bold mb-1">
                                        الإيرادات
                                    </div>
                                    <div class="h2 mb-0 fw-bold">$<?php echo number_format(array_sum($order_amounts), 2); ?></div>
                                    <div class="mt-2 small">
                                        <span class="text-success fw-bold">
                                            <i class="fas fa-arrow-up me-1"></i> 
                                            <?php echo ($plans_stats) ? number_format(max($plan_revenues), 2) : '0.00'; ?> أعلى خطة
                                        </span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-6">
                    <div class="chart-container">
                        <h5 class="mb-4"><i class="fas fa-users me-2 text-primary"></i>توزيع المستخدمين</h5>
                        <canvas id="userStatsChart"></canvas>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="chart-container">
                        <h5 class="mb-4"><i class="fas fa-file-upload me-2 text-success"></i>أنواع التحميلات</h5>
                        <canvas id="uploadsChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-lg-6">
                    <div class="chart-container">
                        <h5 class="mb-4"><i class="fas fa-shopping-cart me-2 text-info"></i>حالات الطلبات</h5>
                        <canvas id="ordersChart"></canvas>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="chart-container">
                        <h5 class="mb-4"><i class="fas fa-boxes me-2 text-warning"></i>توزيع الخطط</h5>
                        <canvas id="plansChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-lg-6">
                    <div class="data-card">
                        <h5 class="mb-4 d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-user-clock me-2 text-primary"></i>آخر المستخدمين</span>
                            <a href="users.php" class="btn btn-sm btn-primary">عرض الكل</a>
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>المستخدم</th>
                                        <th>البريد</th>
                                        <th>الحالة</th>
                                        <th>التسجيل</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($last_users as $user): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['name']); ?>&background=random" 
                                                    class="user-avatar me-2" alt="User">
                                                <div><?php echo htmlspecialchars($user['name']); ?></div>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td>
                                            <?php if($user['banned']): ?>
                                                <span class="status-badge bg-banned">محظور</span>
                                            <?php elseif(!$user['verified']): ?>
                                                <span class="status-badge bg-unverified">غير مفعل</span>
                                            <?php else: ?>
                                                <span class="status-badge bg-success">نشط</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo date('Y-m-d', strtotime($user['created_at'])); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="data-card">
                        <h5 class="mb-4 d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-shopping-bag me-2 text-info"></i>آخر الطلبات</span>
                            <a href="orders.php" class="btn btn-sm btn-primary">عرض الكل</a>
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>رقم الطلب</th>
                                        <th>العميل</th>
                                        <th>الخطة</th>
                                        <th>الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($last_orders as $order): ?>
                                    <tr>
                                        <td>#<?php echo $order['id']; ?></td>
                                        <td><?php echo htmlspecialchars($order['user_name']); ?></td>
                                        <td><?php echo htmlspecialchars($order['plan_name']); ?></td>
                                        <td>
                                            <?php 
                                            $status_class = '';
                                            switch($order['status']) {
                                                case 'pending': $status_class = 'bg-pending'; break;
                                                case 'verified': $status_class = 'bg-verified'; break;
                                                case 'completed': $status_class = 'bg-completed'; break;
                                                case 'rejected': $status_class = 'bg-rejected'; break;
                                            }
                                            ?>
                                            <span class="status-badge <?php echo $status_class; ?>">
                                                <?php 
                                                $status_text = '';
                                                switch($order['status']) {
                                                    case 'pending': $status_text = 'قيد الانتظار'; break;
                                                    case 'verified': $status_text = 'تم التحقق'; break;
                                                    case 'completed': $status_text = 'مكتمل'; break;
                                                    case 'rejected': $status_text = 'مرفوض'; break;
                                                }
                                                echo $status_text;
                                                ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-4 card-show">
                    <div class="data-card">
                        <h5 class="mb-4"><i class="fas fa-user-check me-2 text-success"></i>تفاصيل المستخدمين</h5>
                        <div class="d-flex justify-content-between mb-3">
                            <span>مسجلون عبر البريد:</span>
                            <strong><?php echo number_format($users_stats['email_users']); ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>مسجلون عبر جوجل:</span>
                            <strong><?php echo number_format($users_stats['google_users']); ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>حسابات غير مفعلة:</span>
                            <strong><?php echo number_format($users_stats['unverified_users']); ?></strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>حسابات محظورة:</span>
                            <strong><?php echo number_format($users_stats['banned_users']); ?></strong>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 card-show">
                    <div class="data-card">
                        <h5 class="mb-4"><i class="fas fa-file-alt me-2 text-info"></i>تفاصيل التحميلات</h5>
                        <div class="d-flex justify-content-between mb-3">
                            <span>إجمالي الملفات:</span>
                            <strong><?php echo number_format($uploads_stats['file_uploads']); ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>إجمالي الروابط:</span>
                            <strong><?php echo number_format($uploads_stats['link_uploads']); ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>الحجم الكلي:</span>
                            <strong><?php echo round($uploads_stats['total_file_size'] / (1024 * 1024), 2); ?> MB</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>متوسط حجم الملف:</span>
                            <strong><?php echo round($uploads_stats['avg_file_size'] / 1024, 2); ?> KB</strong>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 card-show">
                    <div class="data-card">
                        <h5 class="mb-4"><i class="fas fa-chart-pie me-2 text-warning"></i>ملخص الإيرادات</h5>
                        <div class="d-flex justify-content-between mb-3">
                            <span>إجمالي الإيرادات:</span>
                            <strong>$<?php echo number_format(array_sum($order_amounts), 2); ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>الطلبات المكتملة:</span>
                            <strong>
                                <?php 
                                    $completed_orders = 0;
                                    foreach($orders_stats as $os) {
                                        if($os['status'] == 'completed') $completed_orders = $os['order_count'];
                                    }
                                    echo number_format($completed_orders);
                                ?>
                            </strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>متوسط قيمة الطلب:</span>
                            <strong>
                                $<?php 
                                    $total_orders = array_sum($order_counts);
                                    $avg_order = ($total_orders > 0) ? array_sum($order_amounts) / $total_orders : 0;
                                    echo number_format($avg_order, 2);
                                ?>
                            </strong>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="mt-5 py-4 text-center text-muted">
                <p>لوحة التحكم - نظام إدارة الموقع | تم التحديث في <?php echo date('Y-m-d H:i:s'); ?></p>
                <p class="mb-0">جميع الحقوق محفوظة &copy; <?php echo date('Y'); ?></p>
            </footer>
        </div>
    </main>
    <div class="screen-size-warning">
        ⚠️ عذراً، الموقع لا يعمل بشكل صحيح على شاشات أصغر من 600px<br>
        الرجاء استخدام جهاز بشاشة أكبر أو تكبير نافذة المتصفح
    </div>
    <!--  -->
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    <!-- ================================================================== -->
    <!-- == تحذير يرجى ترك ملفات chart مدمج مع php لتجنب ظهور اي خطأ == -->
    <!-- ================================================================== -->
    <script>
        const userStatsChart = new Chart(
            document.getElementById('userStatsChart'),
            {
                type: 'doughnut',
                data: {
                    labels: ['مسجلون بالبريد', 'مسجلون بجوجل', 'غير مفعلين', 'محظورين'],
                    datasets: [{
                        data: [
                            <?php echo $users_stats['email_users']; ?>,
                            <?php echo $users_stats['google_users']; ?>,
                            <?php echo $users_stats['unverified_users']; ?>,
                            <?php echo $users_stats['banned_users']; ?>
                        ],
                        backgroundColor: ['#4e73df', '#db4437', '#f6c23e', '#e74a3b'],
                        hoverBackgroundColor: ['#2e59d9', '#c23321', '#dda20a', '#be2617'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            rtl: true,
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        }
                    }
                }
            }
        );

        // إحصائيات التحميلات
        const uploadsChart = new Chart(
            document.getElementById('uploadsChart'),
            {
                type: 'pie',
                data: {
                    labels: ['ملفات', 'روابط'],
                    datasets: [{
                        data: [
                            <?php echo $uploads_stats['file_uploads']; ?>,
                            <?php echo $uploads_stats['link_uploads']; ?>
                        ],
                        backgroundColor: ['#4e73df', '#1cc88a'],
                        hoverBackgroundColor: ['#2e59d9', '#17a673'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            rtl: true,
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        }
                    }
                }
            }
        );

        // إحصائيات الطلبات
        const ordersChart = new Chart(
            document.getElementById('ordersChart'),
            {
                type: 'bar',
                data: {
                    labels: ['قيد الانتظار', 'تم التحقق', 'مكتملة', 'مرفوضة'],
                    datasets: [{
                        label: "عدد الطلبات",
                        backgroundColor: '#4e73df',
                        hoverBackgroundColor: '#2e59d9',
                        borderColor: '#4e73df',
                        data: [
                            <?php 
                                $pending = 0; $verified = 0; $completed = 0; $rejected = 0;
                                foreach($orders_stats as $os) {
                                    if($os['status'] == 'pending') $pending = $os['order_count'];
                                    if($os['status'] == 'verified') $verified = $os['order_count'];
                                    if($os['status'] == 'completed') $completed = $os['order_count'];
                                    if($os['status'] == 'rejected') $rejected = $os['order_count'];
                                }
                                echo "$pending, $verified, $completed, $rejected";
                            ?>
                        ],
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: "rgba(0, 0, 0, .05)",
                            }
                        }
                    }
                }
            }
        );

        // إحصائيات الخطط
        const plansChart = new Chart(
            document.getElementById('plansChart'),
            {
                type: 'polarArea',
                data: {
                    labels: <?php echo json_encode($plan_types); ?>,
                    datasets: [{
                        data: <?php echo json_encode($plan_counts); ?>,
                        backgroundColor: ['#4e73df', '#1cc88a', '#f6c23e', '#36b9cc', '#e74a3b'],
                        hoverBackgroundColor: ['#2e59d9', '#17a673', '#dda20a', '#2a96a5', '#be2617'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            rtl: true,
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        }
                    }
                }
            }
        );
    </script>
</body>
</html>