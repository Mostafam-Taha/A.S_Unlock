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
    <link rel="stylesheet" href="../assets/css/common-questions.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">

    <title>Common questions</title>
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
        <!-- #Common questions -->
        <section class="product">
            <div class="contanier">
                <h3 style="margin-bottom: 10px;">الأسئلة الشائعة</h3>
                <div class="detail-order">
                    <div class="function-top-flex">
                        <div class="flex-top">
                            <div class="function-flex-right" style="position: relative;">
                                <!-- <button id="exportBtn" class="btn-export"><i class="bi bi-download"></i> تصدير البيانات</button> -->
                                <button class="btn-plus bi bi-plus-lg plus-add" data-bs-toggle="modal" data-bs-target="#questionModal"></button>
                            </div>
                            <div class="function-flex-left">
                                <label for="search-table-product">
                                    <i class="bi bi-search"></i>
                                    <input type="search" name="search-table-questions" id="search-table-questions" placeholder="ابحث في الأسئلة أو الأجوبة..." onkeyup="searchTable()">
                                </label>
                            </div>
                        </div>
                        <div class="prod-table">
                            <table id="itemsTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>السؤال</th>
                                        <th>الاجابة</th>
                                        <th>الحالة</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $pdo->query("SELECT * FROM questions");
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        $question_length = strlen($row['question']);
                                        $answer_length = strlen($row['answer']);
                                        
                                        $question_short = substr($row['question'], 0, ceil($question_length * 0.3));
                                        $answer_short = substr($row['answer'], 0, ceil($answer_length * 0.3));
                                        
                                        $status = $row['is_published'] ? '✔' : '❌';
                                        
                                        echo "<tr>
                                            <td>#".$row['id']."</td>
                                            <td data-full-question='".htmlspecialchars($row['question'], ENT_QUOTES)."'>".htmlspecialchars($question_short)."</td>
                                            <td data-full-answer='".htmlspecialchars($row['answer'], ENT_QUOTES)."'>".htmlspecialchars($answer_short)."</td>
                                            <td>".$status."</td>
                                            <td>
                                                <a href='#' class='view-item-".$row['id']."' data-bs-toggle='modal' data-bs-target='#questionModal-".$row['id']."'>عرض</a> | 
                                                <a href='#' class='delete-item' data-id='".$row['id']."'>حذف</a>
                                            </td>
                                        </tr>";
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
    <!-- Modal -->
    <div class="modal fade" id="questionModal" tabindex="-1" aria-labelledby="questionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="questionModalLabel">إضافة سؤال جديد</h5>
                </div>
                <form id="questionForm" action="../api/save_question.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="questionText" class="form-label">السؤال</label>
                            <textarea class="form-control" id="questionText" name="question" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="answerText" class="form-label">الإجابة</label>
                            <textarea class="form-control" id="answerText" name="answer" rows="3" required></textarea>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="publishCheck" name="is_published">
                            <label class="form-check-label" for="publishCheck">نشر السؤال</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--  -->
    <!--  -->
    <!--  -->
    <?php
    $stmt = $pdo->query("SELECT * FROM questions");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    ?>
    <div class="modal fade" id="questionModal-<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="questionModalLabel-<?php echo $row['id']; ?>" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="questionModalLabel-<?php echo $row['id']; ?>">تعديل السؤال #<?php echo $row['id']; ?></h5>
                    <button type="button" style="color: #ddd;" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editForm-<?php echo $row['id']; ?>" method="post" action="../api/update_question.php">
                    <div class="modal-body">
                        <input type="hidden" name="question_id" value="<?php echo $row['id']; ?>">
                        
                        <div class="mb-3">
                            <label for="questionText-<?php echo $row['id']; ?>" class="form-label">السؤال</label>
                            <textarea class="form-control" id="questionText-<?php echo $row['id']; ?>" name="question" rows="3"><?php echo htmlspecialchars($row['question']); ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="answerText-<?php echo $row['id']; ?>" class="form-label">الإجابة</label>
                            <textarea class="form-control" id="answerText-<?php echo $row['id']; ?>" name="answer" rows="5"><?php echo htmlspecialchars($row['answer']); ?></textarea>
                        </div>
                        
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="publishStatus-<?php echo $row['id']; ?>" name="is_published" <?php echo $row['is_published'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="publishStatus-<?php echo $row['id']; ?>">منشور</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php } ?>
    <!-- نافذة تأكيد الحذف -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title">تأكيد الحذف</h5>
                </div>
                <div class="modal-body">
                    <p>هل أنت متأكد من أنك تريد حذف هذا السؤال؟ لا يمكن التراجع عن هذه العملية.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">حذف</button>
                </div>
            </div>
        </div>
    </div>
    <div class="screen-size-warning">
        ⚠️ عذراً، الموقع لا يعمل بشكل صحيح على شاشات أصغر من 600px<br>
        الرجاء استخدام جهاز بشاشة أكبر أو تكبير نافذة المتصفح
    </div>
    <!--  -->
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery (optional, for AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/common-questions.js"></script>
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