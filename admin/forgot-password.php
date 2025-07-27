<?php
require_once '../includes/config.php';
session_start();

$error = '';
$success = '';
$showPasswordForm = false;
$admin = null;

// التحقق من إرسال اسم المستخدم
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = trim($_POST['username']);
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
        
        if (!$admin) {
            $error = 'اسم المستخدم غير موجود';
        } else {
            // جلب نص سؤال الأمان
            $stmt = $pdo->prepare("SELECT question_text FROM security_questions WHERE question_id = ?");
            $stmt->execute([$admin['security_question_id']]);
            $question = $stmt->fetch();
            
            $_SESSION['reset_admin_id'] = $admin['admin_id'];
            $_SESSION['reset_question'] = $question['question_text'];
        }
    } catch (PDOException $e) {
        $error = 'حدث خطأ في الخادم';
    }
}

// التحقق من إجابة سؤال الأمان
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['security_answer'])) {
    if (empty($_SESSION['reset_admin_id'])) {
        $error = 'الجلسة انتهت، يرجى المحاولة مرة أخرى';
    } else {
        $answer = trim($_POST['security_answer']);
        $hashedAnswer = hash('sha256', strtolower($answer));
        
        try {
            $stmt = $pdo->prepare("SELECT admin_id FROM admins WHERE admin_id = ? AND security_answer_hash = ?");
            $stmt->execute([$_SESSION['reset_admin_id'], $hashedAnswer]);
            $admin = $stmt->fetch();
            
            if ($admin) {
                $showPasswordForm = true;
                $success = 'إجابة صحيحة، يرجى إدخال كلمة المرور الجديدة';
            } else {
                $error = 'إجابة خاطئة، يرجى المحاولة مرة أخرى';
            }
        } catch (PDOException $e) {
            $error = 'حدث خطأ في الخادم';
        }
    }
}

// تحديث كلمة المرور الجديدة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
    if (empty($_SESSION['reset_admin_id'])) {
        $error = 'الجلسة انتهت، يرجى المحاولة مرة أخرى';
    } else {
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];
        
        if ($newPassword !== $confirmPassword) {
            $error = 'كلمة المرور وتأكيدها غير متطابقين';
            $showPasswordForm = true;
        } elseif (strlen($newPassword) < 8) {
            $error = 'كلمة المرور يجب أن تحتوي على 8 أحرف على الأقل';
            $showPasswordForm = true;
        } else {
            $passwordHash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
            
            try {
                $stmt = $pdo->prepare("UPDATE admins SET password_hash = ? WHERE admin_id = ?");
                $stmt->execute([$passwordHash, $_SESSION['reset_admin_id']]);
                
                session_unset();
                session_destroy();
                
                $success = 'تم تحديث كلمة المرور بنجاح، يمكنك تسجيل الدخول الآن';
                $showPasswordForm = false;
            } catch (PDOException $e) {
                $error = 'حدث خطأ في تحديث كلمة المرور';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استعادة كلمة المرور</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --br-primary-color: linear-gradient(90deg, #1976D2, #42A5F5);
            --br-color-h-p: #1976D2;
            --br-sacn-color: #23234a;
            --br-links-color: #495057;
            --br-border-color: #dfe1e5;
            --br-btn-padding: 7px 22px;
            --br-box-shadow: 0px 0px 0px 5px #1976d254;
            --br-dir-none: none;
            --br-font-w-text: 400;
            --br-matgin-width: 0 100px;
            
    
            --success-color: #28a745;
            --error-color: #dc3545;
            --warning-color: #ffc107;
            --border-radius: 8px;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            font-family: "Tajawal", sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: "Tajawal", sans-serif;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        h1, h2, h3, h4, h5, h6 {
            margin: 0;
            padding: 0;
            color: var(--br-sacn-color);
            font-weight: 500;
        }

        p {
            margin: 0;
            padding: 0;
            color: var(--br-sacn-color);
        }

        a {
            text-decoration: none;
            color: var(--br-sacn-color);
        }

        .password-container {
            max-width: 500px;
            width: 100%;
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
            transition: var(--transition);
            margin: 20px;
        }

        .password-header {
            background: var(--br-primary-color);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        .password-header h2 {
            font-weight: 500;
            margin-bottom: 0;
        }

        .password-body {
            padding: 2rem;
        }

        .form-control {
            border-radius: var(--border-radius);
            padding: 0.75rem 1.25rem;
            border: 1px solid var(--br-border-color);
            transition: var(--transition);
            font-family: "Tajawal", sans-serif;
        }

        .form-control:focus {
            border-color: var(--br-color-h-p);
            box-shadow: var(--br-box-shadow);
        }

        .input-group-text {
            background-color: transparent;
            border-right: none;
            color: var(--br-color-h-p);
        }

        .btn-primary {
            background: var(--br-primary-color);
            border: none;
            border-radius: var(--border-radius);
            padding: var(--br-btn-padding);
            font-weight: 500;
            transition: var(--transition);
            height: 45px;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .security-question {
            background-color: #f8f9fa;
            border-radius: var(--border-radius);
            padding: 1rem;
            margin: 1.5rem 0;
            font-weight: 500;
            border-left: 4px solid var(--br-color-h-p);
        }

        .alert {
            border-radius: var(--border-radius);
            font-family: "Tajawal", sans-serif;
        }

        .password-strength {
            height: 5px;
            background-color: #e9ecef;
            border-radius: 5px;
            margin-top: 0.5rem;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0%;
            background-color: var(--error-color);
            transition: var(--transition);
        }


        .btn-uiverse {
            position: relative;
            overflow: hidden;
        }

        .btn-uiverse::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }

        .btn-uiverse:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }

        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            100% {
                transform: scale(20, 20);
                opacity: 0;
            }
        }

        .text-muted {
            color: var(--br-links-color) !important;
        }

        .text-decoration-none {
            color: var(--br-color-h-p);
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="password-container animate__animated animate__fadeIn">
                    <div class="password-header">
                        <h2><i class="fas fa-key me-2"></i>استعادة كلمة المرور</h2>
                    </div>
                    
                    <div class="password-body">
                        <?php if ($error): ?>
                        <div class="alert alert-danger animate__animated animate__shakeX">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                        <div class="alert alert-success animate__animated animate__fadeIn">
                            <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!$showPasswordForm && empty($_SESSION['reset_question'])): ?>
                        <form method="POST">
                            <div class="mb-4">
                                <label for="username" class="form-label">اسم المستخدم</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="أدخل اسم المستخدم" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-uiverse w-100">
                                <i class="fas fa-arrow-left me-2"></i>التالي
                            </button>
                        </form>
                        <?php endif; ?>
                        
                        <?php if (!$showPasswordForm && !empty($_SESSION['reset_question'])): ?>
                        <form method="POST">
                            <div class="security-question animate__animated animate__fadeIn">
                                <i class="fas fa-question-circle me-2"></i><?php echo $_SESSION['reset_question']; ?>
                            </div>
                            <div class="mb-4">
                                <label for="security_answer" class="form-label">إجابة سؤال الأمان</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                                    <input type="text" class="form-control" id="security_answer" name="security_answer" placeholder="أدخل إجابة سؤال الأمان" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-uiverse w-100">
                                <i class="fas fa-check me-2"></i>تحقق
                            </button>
                        </form>
                        <?php endif; ?>
                        
                        <?php if ($showPasswordForm): ?>
                        <form method="POST">
                            <div class="mb-4">
                                <label for="new_password" class="form-label">كلمة المرور الجديدة</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="أدخل كلمة المرور الجديدة" required>
                                </div>
                                <div class="password-strength">
                                    <div class="password-strength-bar" id="password-strength-bar"></div>
                                </div>
                                <small class="text-muted">يجب أن تحتوي على 8 أحرف على الأقل</small>
                            </div>
                            <div class="mb-4">
                                <label for="confirm_password" class="form-label">تأكيد كلمة المرور</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="أعد إدخال كلمة المرور الجديدة" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-uiverse w-100">
                                <i class="fas fa-sync-alt me-2"></i>تحديث كلمة المرور
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if (!$showPasswordForm && empty($_SESSION['reset_question'])): ?>
                <div class="text-center mt-4 animate__animated animate__fadeInUp">
                    <p class="text-muted">تذكرت كلمة المرور؟ <a href="logs.php" class="text-decoration-none">تسجيل الدخول</a></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('new_password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('password-strength-bar');
            let strength = 0;
            
            if (password.length >= 8) strength += 1;
            if (password.match(/[a-z]/)) strength += 1;
            if (password.match(/[A-Z]/)) strength += 1;
            if (password.match(/[0-9]/)) strength += 1;
            if (password.match(/[^a-zA-Z0-9]/)) strength += 1;
            
            const width = (strength / 5) * 100;
            strengthBar.style.width = width + '%';
            
            if (strength <= 2) {
                strengthBar.style.backgroundColor = 'var(--error-color)';
            } else if (strength <= 4) {
                strengthBar.style.backgroundColor = 'var(--warning-color)';
            } else {
                strengthBar.style.backgroundColor = 'var(--success-color)';
            }
        });
    </script>
</body>
</html>