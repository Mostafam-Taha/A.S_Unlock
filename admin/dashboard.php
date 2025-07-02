<?php
require_once '../includes/config.php';

session_start();

// التحقق من تسجيل الدخول
if (empty($_SESSION['admin_id'])) {
    header('Location: logs.php');
    exit;
}

// جلب بيانات المسؤول
$stmt = $pdo->prepare("SELECT * FROM admins WHERE admin_id = ?");
$stmt->execute([$_SESSION['admin_id']]);
$admin = $stmt->fetch();

if (!$admin) {
    session_destroy();
    header('Location: logs.php');
    exit;
}

// جلب سجل الدخول
$stmt = $pdo->prepare("
    SELECT * FROM login_attempts 
    WHERE admin_id = ? 
    ORDER BY attempt_time DESC 
    LIMIT 10
");
$stmt->execute([$_SESSION['admin_id']]);
$loginHistory = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم</title>
    <!-- إضافة روابط CSS و JS -->
</head>
<body>
    <div class="dashboard-container">
        <h1>مرحباً <?php echo htmlspecialchars($admin['username']); ?></h1>
        
        <div class="login-history">
            <h3>سجل الدخول الأخير</h3>
            <table>
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>عنوان IP</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($loginHistory as $attempt): ?>
                    <tr>
                        <td><?php echo $attempt['attempt_time']; ?></td>
                        <td><?php echo $attempt['ip_address']; ?></td>
                        <td><?php echo $attempt['is_success'] ? 'ناجح' : 'فاشل'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <a href="logout.php" class="logout-btn">تسجيل الخروج</a>
    </div>
</body>
</html>