<?php
session_start();

// جلب رسائل الخطأ من الجلسة إن وجدت
$error = $_SESSION['error'] ?? '';
$login_error = $_SESSION['login_error'] ?? '';
$old_email = $_SESSION['old_email'] ?? '';
unset($_SESSION['error'], $_SESSION['login_error'], $_SESSION['old_email']);

require_once '../includes/config.php';

// إذا كان المستخدم مسجل الدخول بالفعل، توجيهه للصفحة الرئيسية
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

// معالجة بيانات تسجيل الدخول إذا تم إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // التحقق من حالة الحظر
            if ($user['banned']) {
                $_SESSION['login_error'] = 'تم حظر حسابك. الرجاء التواصل مع الإدارة.';
                $_SESSION['old_email'] = $email;
                header('Location: login.php');
                exit();
            }
            
            // تسجيل الدخول الناجح
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_avatar'] = $user['avatar'];
            
            // تذكرني
            if (isset($_POST['remember'])) {
                $token = bin2hex(random_bytes(32));
                $expiry = date('Y-m-d H:i:s', time() + 30 * 24 * 60 * 60); // 30 يوم
                
                $stmt = $pdo->prepare("UPDATE users SET remember_token = ?, token_expiry = ? WHERE id = ?");
                $stmt->execute([$token, $expiry, $user['id']]);
                
                setcookie('remember_token', $token, time() + 30 * 24 * 60 * 60, '/', '', true, true);
            }
            
            header('Location: products.php');
            exit();
        } else {
            $_SESSION['error'] = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
            $_SESSION['old_email'] = $email;
            header('Location: login.php');
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = 'حدث خطأ في النظام. الرجاء المحاولة لاحقاً.';
        header('Location: login.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap"rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/login.css">
    <title>A.S UNLOCK - Login</title>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="login-container">
                    <div class="logo">
                        <img src="../assets/image/favicon.ico" alt="A.S Unlock Logo" class="img-fluid">
                        <h1 class="mb-3">مرحباً بعودتك</h1>
                        <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        <?php if ($login_error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($login_error) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <form action="login.php" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <div class="input-with-icon">
                                <i class="fas fa-envelope"></i>
                                <input type="email" class="form-control" id="email" name="email" placeholder="أدخل بريدك الإلكتروني" required value="<?= htmlspecialchars($old_email) ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة المرور</label>
                            <div class="input-with-icon">
                                <i class="fas fa-lock"></i>
                                <input type="password" class="form-control" id="password" name="password" placeholder="أدخل كلمة المرور" required>
                                <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                            </div>
                        </div>
                        
                        <div class="remember-forgot">
                            <div class="remember-me">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label" for="remember">تذكرني</label>
                            </div>
                            <!-- <input type="hidden"> -->
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            تسجيل الدخول
                        </button>
                        
                        <div class="divider">أو</div>
                        
                        <!-- <button type="button" class="btn btn-google" id="googleSignIn">
                            <span>تسجيل الدخول عبر جوجل</span>
                            <i class="fab fa-google"></i>
                        </button> -->
                        
                        <div id="g_id_onload"
                            data-client_id="743085929867-r6qf37qh4udps5sqeu1dln3tnqerc77t.apps.googleusercontent.com"
                            data-context="signin"
                            data-ux_mode="popup"
                            data-callback="handleGoogleLogin"
                            data-itp_support="true">
                        </div>

                        <div class="text-center my-3"> <!-- أضفنا div مع class لتوسيط المحتوى -->
                            <div class="g_id_signin d-inline-block"
                                data-type="standard"
                                data-shape="pill"
                                data-theme="outline"
                                data-text="signin_with"
                                data-size="large"
                                data-locale="ar"
                                data-logo_alignment="left">
                            </div>
                        </div>
                    </form>
                    
                    <div class="signup-link">
                        ليس لديك حساب؟ <a href="register.php">إنشاء حساب جديد</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://accounts.google.com/gsi/client" async></script>
    <script>
        // عرض/إخفاء كلمة المرور
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
        
        // معالجة تسجيل الدخول عبر جوجل
        function handleGoogleLogin(response) {
            fetch('../google-auth/google-login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `credential=${encodeURIComponent(response.credential)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'products.php';
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء تسجيل الدخول عبر جوجل');
            });
        }
        
        // إمكانية استخدام AJAX للنموذج العادي (اختياري)
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    return response.json().then(data => {
                        if (data.success) {
                            window.location.href = 'products.php';
                        } else {
                            alert(data.message);
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Fallback to normal form submission if AJAX fails
                form.submit();
            });
        });
    </script>
</body>
</html>