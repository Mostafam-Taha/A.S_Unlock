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
            
            header('Location: checkout.php?product_id=12&plan_id=47&step=1');
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
    <title>A.S Unlock - تسجيل الدخول</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #6e8efb;
            --secondary-color: #a777e3;
            --dark-color: #2d3748;
            --light-color: #f7fafc;
            --danger-color: #e53e3e;
            --success-color: #48bb78;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Tajawal', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            color: var(--dark-color);
        }
        
        .login-container {
            display: flex;
            max-width: 900px;
            width: 90%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            animation: fadeIn 0.8s ease-in-out;
        }
        
        .login-image {
            flex: 1;
            background: url('https://images.unsplash.com/photo-1550751827-4bd374c3f58b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80') center/cover no-repeat;
            display: none;
        }
        
        .login-form {
            flex: 1;
            padding: 40px;
            position: relative;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo img {
            height: 50px;
        }
        
        .logo h1 {
            color: var(--dark-color);
            font-size: 24px;
            margin-top: 10px;
        }
        
        .alert {
            padding: 12px;
            margin: 15px 0;
            border-radius: 8px;
            font-size: 14px;
            text-align: center;
        }
        
        .alert-danger {
            background-color: #fef2f2;
            color: var(--danger-color);
            border: 1px solid #fee2e2;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-color);
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .input-with-icon input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(110, 142, 251, 0.2);
            outline: none;
        }
        
        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #a0aec0;
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
        }
        
        .remember-me input {
            margin-left: 8px;
        }
        
        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .forgot-password:hover {
            text-decoration: underline;
        }
        
        .btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-primary {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            margin-bottom: 20px;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(110, 142, 251, 0.4);
        }
        
        .btn-google {
            background: white;
            color: #4285F4;
            border: 2px solid #e2e8f0;
        }
        
        .btn-google:hover {
            background: #f8fafc;
        }
        
        .btn-google i {
            margin-left: 10px;
            font-size: 18px;
            color: #4285F4;
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
            color: #a0aec0;
        }
        
        .divider::before, .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .divider::before {
            margin-left: 10px;
        }
        
        .divider::after {
            margin-right: 10px;
        }
        
        .signup-link {
            text-align: center;
            margin-top: 20px;
            color: #4a5568;
        }
        
        .signup-link a {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
        }
        
        .signup-link a:hover {
            text-decoration: underline;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (min-width: 768px) {
            .login-image {
                display: block;
            }
        }
        
        @media (max-width: 480px) {
            .login-form {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-image"></div>
        <div class="login-form">
            <div class="logo">
                <img src="../assets/image/favicon.ico" alt="A.S Unlock Logo">
                <h1>مرحباً بعودتك</h1>
                <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if ($login_error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($login_error) ?></div>
                <?php endif; ?>
            </div>
            
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="email">البريد الإلكتروني</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="أدخل بريدك الإلكتروني" required value="<?= htmlspecialchars($old_email) ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">كلمة المرور</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="أدخل كلمة المرور" required>
                        <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                    </div>
                </div>
                
                <div class="remember-forgot">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">تذكرني</label>
                    </div>
                    <a href="forgot-password.php" class="forgot-password">نسيت كلمة المرور؟</a>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    تسجيل الدخول
                </button>
                
                <div class="divider">أو</div>
                
                <button type="button" class="btn btn-google" id="googleSignIn">
                    <span>تسجيل الدخول عبر جوجل</span>
                    <i class="fab fa-google"></i>
                </button>
                
                <div id="g_id_onload"
                    data-client_id="743085929867-r6qf37qh4udps5sqeu1dln3tnqerc77t.apps.googleusercontent.com"
                    data-context="signin"
                    data-ux_mode="popup"
                    data-callback="handleGoogleLogin"
                    data-itp_support="true">
                </div>

                <div class="g_id_signin"
                    data-type="standard"
                    data-shape="pill"
                    data-theme="outline"
                    data-text="signin_with"
                    data-size="large"
                    data-locale="ar"
                    data-logo_alignment="left">
                </div>
            </form>
            
            <div class="signup-link">
                ليس لديك حساب؟ <a href="register.php">إنشاء حساب جديد</a>
            </div>
        </div>
    </div>

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
                    window.location.href = 'checkout.php?product_id=12&plan_id=47&step=1';
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
                            window.location.href = 'checkout.php?product_id=12&plan_id=47&step=1';
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