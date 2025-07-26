<?php
session_start();
// جلب رسائل الخطأ من الجلسة إن وجدت
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="../assets/css/register.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
    
    <title>A.S Unlock - إنشاء حساب جديد</title>
</head>
<body>
    <div class="register-container">
        <div class="logo">
            <img src="../assets/image/favicon.ico" alt="A.S Unlock Logo">
            <h1>إنشاء حساب جديد</h1>
            <?php if (isset($errors['general'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errors['general']) ?></div>
            <?php endif; ?>
        </div>
        
        <form action="../includes/register-process.php" method="POST">
            <div class="form-group">
                <label for="name">الاسم الكامل</label>
                <input type="text" id="name" name="name" placeholder="أدخل اسمك الكامل" required value="<?= htmlspecialchars($old['name'] ?? '') ?>">
                <?php if (isset($errors['name'])): ?>
                <span class="error-message"><?= htmlspecialchars($errors['name']) ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" placeholder="أدخل بريدك الإلكتروني" required value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                <?php if (isset($errors['email'])): ?>
                <span class="error-message"><?= htmlspecialchars($errors['email']) ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <div style="position: relative;">
                    <input type="password" id="password" name="password" placeholder="أدخل كلمة المرور (8 أحرف على الأقل)" required>
                    <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                </div>
                <?php if (isset($errors['password'])): ?>
                <span class="error-message"><?= htmlspecialchars($errors['password']) ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">تأكيد كلمة المرور</label>
                <div style="position: relative;">
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="أعد إدخال كلمة المرور" required>
                    <i class="fas fa-eye password-toggle" id="toggleConfirmPassword"></i>
                </div>
                <?php if (isset($errors['confirm_password'])): ?>
                <span class="error-message"><?= htmlspecialchars($errors['confirm_password']) ?></span>
                <?php endif; ?>
            </div>
            
            <button type="submit" class="btn btn-primary">
                إنشاء حساب
            </button>
        </form>
        
        <div class="login-link">
            لديك حساب بالفعل؟ <a href="login.php">تسجيل الدخول</a>
        </div>
    </div>

    <script>
        // عرض/إخفاء كلمة المرور
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
        
        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const confirmInput = document.getElementById('confirm_password');
            const type = confirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });
        
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword && confirmPassword.length > 0) {
                this.style.borderColor = 'var(--danger-color)';
            } else {
                this.style.borderColor = 'var(--br-border-color)';
            }
        });
    </script>
</body>
</html>