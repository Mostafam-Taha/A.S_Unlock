<?php
require '../includes/config.php';
session_start();

// التأكد أن الطلب POST فقط
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../public/login.php');
    exit;
}

// استقبال البيانات
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);

// حفظ البريد للإعادة إن كان هناك خطأ
$_SESSION['old_email'] = $email;

// التحقق من البيانات
if (empty($email) || empty($password)) {
    $_SESSION['error'] = 'جميع الحقول مطلوبة';
    header('Location: ../public/login.php');
    exit;
}

try {
    // البحث عن المستخدم
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user || !password_verify($password, $user['password'])) {
        $_SESSION['error'] = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
        header('Location: ../public/login.php');
        exit;
    }
    
    // تسجيل الدخول الناجح
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_name'] = $user['name'];
    
    // إذا اختار "تذكرني"
    if ($remember) {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
        
        $stmt = $pdo->prepare("UPDATE users SET remember_token = ?, token_expires = ? WHERE id = ?");
        $stmt->execute([$token, $expires, $user['id']]);
        
        setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', true, true);
    }
    
    // إعادة التوجيه للصفحة الرئيسية
    header('Location: ../public/index.html');
    exit;

} catch (PDOException $e) {
    $_SESSION['error'] = 'حدث خطأ في الخادم';
    header('Location: ../public/login.php');
    exit;
}
?>