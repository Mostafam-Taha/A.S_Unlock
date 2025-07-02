<?php
session_start();
require '../includes/config.php';

// التأكد أن الطلب POST فقط
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../public/register.php');
    exit;
}

// استقبال البيانات
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// حفظ البيانات القديمة للإعادة إن كان هناك خطأ
$_SESSION['old'] = [
    'name' => $name,
    'email' => $email
];

// مصفوفة الأخطاء
$errors = [];

// التحقق من البيانات
if (empty($name)) {
    $errors['name'] = 'الاسم الكامل مطلوب';
} elseif (strlen($name) < 3) {
    $errors['name'] = 'الاسم يجب أن يكون 3 أحرف على الأقل';
}

if (empty($email)) {
    $errors['email'] = 'البريد الإلكتروني مطلوب';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'البريد الإلكتروني غير صحيح';
} else {
    // التحقق من أن البريد غير مسجل مسبقاً
    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            $errors['email'] = 'البريد الإلكتروني مسجل مسبقاً';
        }
    } catch (PDOException $e) {
        $errors['general'] = 'حدث خطأ في الخادم';
    }
}

if (empty($password)) {
    $errors['password'] = 'كلمة المرور مطلوبة';
} elseif (strlen($password) < 8) {
    $errors['password'] = 'كلمة المرور يجب أن تكون 8 أحرف على الأقل';
}

if (empty($confirm_password)) {
    $errors['confirm_password'] = 'تأكيد كلمة المرور مطلوب';
} elseif ($password !== $confirm_password) {
    $errors['confirm_password'] = 'كلمتا المرور غير متطابقتين';
}

// إذا كان هناك أخطاء، إعادة التوجيه مع الأخطاء
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: ../public/register.php');
    exit;
}

// إذا لم يكن هناك أخطاء، تسجيل المستخدم
try {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$name, $email, $hashedPassword]);
    
    // تنظيف الجلسة
    unset($_SESSION['old']);
    
    // إرسال البريد الإلكتروني الترحيبي (اختياري)
    // sendWelcomeEmail($email, $name);
    
    // إعادة التوجيه لصفحة تسجيل الدخول مع رسالة نجاح
    $_SESSION['success'] = 'تم إنشاء الحساب بنجاح! يمكنك تسجيل الدخول الآن';
    header('Location: ../public/login.php');
    exit;

} catch (PDOException $e) {
    $_SESSION['errors']['general'] = 'حدث خطأ أثناء إنشاء الحساب: ' . $e->getMessage();
    header('Location: ../public/register.php');
    exit;
}
?>