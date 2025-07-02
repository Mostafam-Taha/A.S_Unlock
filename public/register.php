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
    <title>A.S Unlock - إنشاء حساب جديد</title>
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
        
        .register-container {
            display: flex;
            max-width: 900px;
            width: 90%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            animation: fadeIn 0.8s ease-in-out;
        }
        
        .register-image {
            flex: 1;
            background: url('https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80') center/cover no-repeat;
            display: none;
        }
        
        .register-form {
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
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #4a5568;
        }
        
        .login-link a {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        .error-message {
            color: var(--danger-color);
            font-size: 14px;
            margin-top: 5px;
            display: block;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (min-width: 768px) {
            .register-image {
                display: block;
            }
        }
        
        @media (max-width: 480px) {
            .register-form {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-image"></div>
        <div class="register-form">
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
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="name" name="name" placeholder="أدخل اسمك الكامل" required value="<?= htmlspecialchars($old['name'] ?? '') ?>">
                    </div>
                    <?php if (isset($errors['name'])): ?>
                    <span class="error-message"><?= htmlspecialchars($errors['name']) ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="email">البريد الإلكتروني</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="أدخل بريدك الإلكتروني" required value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                    </div>
                    <?php if (isset($errors['email'])): ?>
                    <span class="error-message"><?= htmlspecialchars($errors['email']) ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="password">كلمة المرور</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="أدخل كلمة المرور (8 أحرف على الأقل)" required>
                        <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                    </div>
                    <?php if (isset($errors['password'])): ?>
                    <span class="error-message"><?= htmlspecialchars($errors['password']) ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">تأكيد كلمة المرور</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
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
        
        // التحقق من تطابق كلمات المرور أثناء الكتابة
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword && confirmPassword.length > 0) {
                this.style.borderColor = 'var(--danger-color)';
            } else {
                this.style.borderColor = '#e2e8f0';
            }
        });
        
        // إمكانية استخدام AJAX للنموذج (اختياري)
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
                            window.location.href = 'login.php?registered=true';
                        } else {
                            // عرض الأخطاء
                            if (data.errors) {
                                Object.keys(data.errors).forEach(key => {
                                    const errorElement = document.createElement('span');
                                    errorElement.className = 'error-message';
                                    errorElement.textContent = data.errors[key];
                                    
                                    const inputGroup = form.querySelector(`[name="${key}"]`).parentNode;
                                    const existingError = inputGroup.nextElementSibling;
                                    
                                    if (existingError && existingError.classList.contains('error-message')) {
                                        existingError.remove();
                                    }
                                    
                                    inputGroup.parentNode.insertBefore(errorElement, inputGroup.nextSibling);
                                });
                            }
                            
                            if (data.message) {
                                alert(data.message);
                            }
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