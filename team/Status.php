<?php
// اتصال بقاعدة البيانات
require_once('../includes/config.php');

// استعلامات SQL للحصول على البيانات
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

// تنفيذ الاستعلامات
$users_stats = $pdo->query($users_query)->fetch(PDO::FETCH_ASSOC);
$uploads_stats = $pdo->query($uploads_query)->fetch(PDO::FETCH_ASSOC);
$plans_stats = $pdo->query($plans_query)->fetchAll(PDO::FETCH_ASSOC);
$orders_stats = $pdo->query($orders_query)->fetchAll(PDO::FETCH_ASSOC);

// تحضير البيانات للرسوم البيانية
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

// جلب آخر المستخدمين
$last_users_query = "SELECT id, name, email, created_at, verified, banned 
                     FROM users 
                     ORDER BY created_at DESC 
                     LIMIT 5";
$last_users = $pdo->query($last_users_query)->fetchAll(PDO::FETCH_ASSOC);

// جلب آخر الطلبات
$last_orders_query = "SELECT o.id, u.name AS user_name, p.plan_name, o.amount, o.status, o.created_at 
                      FROM orders o
                      JOIN users u ON o.user_id = u.id
                      JOIN product_plans p ON o.plan_id = p.id
                      ORDER BY o.created_at DESC 
                      LIMIT 5";
$last_orders = $pdo->query($last_orders_query)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة إحصائيات الموقع</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #4e73df;
            --secondary: #858796;
            --success: #1cc88a;
            --info: #36b9cc;
            --warning: #f6c23e;
            --danger: #e74a3b;
            --light: #f8f9fc;
            --dark: #5a5c69;
        }
        
        body {
            background-color: #f8f9fc;
            font-family: 'Tajawal', sans-serif;
            padding-top: 20px;
        }
        
        .stat-card {
            border-left: 0.25rem solid var(--primary);
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card.success {
            border-left-color: var(--success);
        }
        
        .stat-card.info {
            border-left-color: var(--info);
        }
        
        .stat-card.warning {
            border-left-color: var(--warning);
        }
        
        .stat-card.danger {
            border-left-color: var(--danger);
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary) 0%, #2a3f9d 100%);
            color: white;
            border-radius: 0.5rem;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .data-card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            padding: 20px;
            margin-bottom: 30px;
            height: 100%;
        }
        
        .badge-pill {
            border-radius: 10rem;
            padding: 0.5em 0.8em;
            font-weight: normal;
        }
        
        .table th {
            font-weight: 700;
            color: var(--dark);
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .status-badge {
            padding: 0.5em 0.8em;
            border-radius: 0.5rem;
            font-size: 0.85rem;
        }
        
        .bg-pending {
            background-color: #f6c23e;
            color: #000;
        }
        
        .bg-verified {
            background-color: #36b9cc;
            color: #fff;
        }
        
        .bg-completed {
            background-color: #1cc88a;
            color: #fff;
        }
        
        .bg-rejected {
            background-color: #e74a3b;
            color: #fff;
        }
        
        .bg-unverified {
            background-color: #f6c23e;
            color: #000;
        }
        
        .bg-banned {
            background-color: #e74a3b;
            color: #fff;
        }
        
        .bg-google {
            background-color: #db4437;
            color: #fff;
        }
        
        .bg-email {
            background-color: #4285f4;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- عنوان الصفحة -->
        <div class="dashboard-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1><i class="fas fa-chart-line me-3"></i>لوحة إحصائيات الموقع</h1>
                    <p class="mb-0">عرض تفصيلي لإحصائيات الموقع والبيانات الحيوية</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-inline-block bg-white text-dark p-2 rounded">
                        <i class="fas fa-calendar-alt me-2 text-primary"></i>
                        <?php echo date('Y-m-d'); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- بطاقات الإحصائيات السريعة -->
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

        <!-- الرسوم البيانية -->
        <div class="row mt-4">
            <!-- إحصائيات المستخدمين -->
            <div class="col-lg-6">
                <div class="chart-container">
                    <h5 class="mb-4"><i class="fas fa-users me-2 text-primary"></i>توزيع المستخدمين</h5>
                    <canvas id="userStatsChart"></canvas>
                </div>
            </div>

            <!-- إحصائيات التحميلات -->
            <div class="col-lg-6">
                <div class="chart-container">
                    <h5 class="mb-4"><i class="fas fa-file-upload me-2 text-success"></i>أنواع التحميلات</h5>
                    <canvas id="uploadsChart"></canvas>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            <!-- إحصائيات الطلبات -->
            <div class="col-lg-6">
                <div class="chart-container">
                    <h5 class="mb-4"><i class="fas fa-shopping-cart me-2 text-info"></i>حالات الطلبات</h5>
                    <canvas id="ordersChart"></canvas>
                </div>
            </div>

            <!-- إحصائيات الخطط -->
            <div class="col-lg-6">
                <div class="chart-container">
                    <h5 class="mb-4"><i class="fas fa-boxes me-2 text-warning"></i>توزيع الخطط</h5>
                    <canvas id="plansChart"></canvas>
                </div>
            </div>
        </div>

        <!-- الجداول التفصيلية -->
        <div class="row mt-2">
            <!-- آخر المستخدمين -->
            <div class="col-lg-6">
                <div class="data-card">
                    <h5 class="mb-4 d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-user-clock me-2 text-primary"></i>آخر المستخدمين</span>
                        <a href="#" class="btn btn-sm btn-primary">عرض الكل</a>
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

            <!-- آخر الطلبات -->
            <div class="col-lg-6">
                <div class="data-card">
                    <h5 class="mb-4 d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-shopping-bag me-2 text-info"></i>آخر الطلبات</span>
                        <a href="#" class="btn btn-sm btn-primary">عرض الكل</a>
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

        <!-- إحصائيات إضافية -->
        <div class="row mt-4">
            <div class="col-md-4">
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
            
            <div class="col-md-4">
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
            
            <div class="col-md-4">
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

        <!-- تذييل الصفحة -->
        <footer class="mt-5 py-4 text-center text-muted">
            <p>لوحة التحكم - نظام إدارة الموقع | تم التحديث في <?php echo date('Y-m-d H:i:s'); ?></p>
            <p class="mb-0">جميع الحقوق محفوظة &copy; <?php echo date('Y'); ?></p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // إحصائيات المستخدمين
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