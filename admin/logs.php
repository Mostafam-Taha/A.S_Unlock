<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Primary -->
    <meta name="title" content="A.S UNLOCK" />
    <meta name="description"
        content="A.S UNLOCK - خبراء فتح وإصلاح أجهزة التابلت بأحدث التقنيات. خدمات سريعة ومضمونة مع دعم على مدار الساعة." />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://as_unlock.ct.ws" />
    <meta property="og:title" content="A.S UNLOCK" />
    <meta property="og:description"
        content="A.S UNLOCK - خبراء فتح وإصلاح أجهزة التابلت بأحدث التقنيات. خدمات سريعة ومضمونة مع دعم على مدار الساعة." />
    <meta property="og:image" content="https://as_unlock.ct.ws/" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="https://as_unlock.ct.ws/assets/image/favicon.ico" />
    <meta property="twitter:title" content="A.S UNLOCK" />
    <meta property="twitter:description"
        content="A.S UNLOCK - خبراء فتح وإصلاح أجهزة التابلت بأحدث التقنيات. خدمات سريعة ومضمونة مع دعم على مدار الساعة." />
    <meta property="twitter:image" content="https://as_unlock.ct.ws/assets/image/favicon.ico" />

    <!-- Links -->
    <link rel="icon" type="image/x-icon" href="../assets/image/favicon.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/logs.css">
    
    <title>A.S UNLOCK - Admin</title>
</head>
<body>
    <div class="login-container">
        <div class="login-form-section">
            <div class="login-header">
                <h2>مرحباً بعودتك!</h2>
                <p>الرجاء إدخال بيانات الاعتماد الخاصة بك للوصول إلى لوحة التحكم</p>
            </div>
            
            <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?php echo $error; ?></span>
            </div>
            <?php endif; ?>
            
            <form id="loginForm" method="POST" action="prosess_logs_admin.php">
                <div class="input-group">
                    <input type="text" id="username" name="username" placeholder="اسم المستخدم" required>
                    <i class="fas fa-user"></i>
                </div>
                
                <div class="input-group">
                    <input type="password" id="password" name="password" placeholder="كلمة المرور" required>
                    <i class="fas fa-lock"></i>
                </div>
                
                <div class="remember-forgot">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">تذكرني</label>
                    </div>
                    <div class="forgot-password">
                        <a href="forgot-password.php">نسيت كلمة المرور؟</a>
                    </div>
                </div>
                
                <button type="submit" class="login-btn">تسجيل الدخول</button>
            </form>
            
            <div class="login-footer">
                <p>ليس لديك حساب؟ <a href="regs.php">إنشاء حساب جديد</a></p>
            </div>
        </div>
        <div class="login-image-section"></div>
    </div>
    <script src="../assets/js/regs_admin.js">
    </script>
    </script>
</body>
</html>