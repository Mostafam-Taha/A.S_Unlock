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
        
        if (strlen($newPassword) < 8) {
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .forgot-container {
            width: 500px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
        }
        
        .forgot-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .forgot-header h2 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }
        
        .input-group input {
            width: 100%;
            padding: 15px 15px 15px 40px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .input-group i {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            color: #777;
        }
        
        .btn {
            width: 100%;
            padding: 15px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        
        .btn:hover {
            background-color: #2980b9;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .alert-error {
            background-color: #fadbd8;
            color: #e74c3c;
        }
        
        .alert-success {
            background-color: #d5f5e3;
            color: #27ae60;
        }
        
        .question-text {
            margin: 20px 0;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-header">
            <h2>استعادة كلمة المرور</h2>
        </div>
        
        <?php if ($error): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
        </div>
        <?php endif; ?>
        
        <?php if (!$showPasswordForm && empty($_SESSION['reset_question'])): ?>
        <form method="POST">
            <div class="input-group">
                <input type="text" name="username" placeholder="اسم المستخدم" required>
                <i class="fas fa-user"></i>
            </div>
            <button type="submit" class="btn">التالي</button>
        </form>
        <?php endif; ?>
        
        <?php if (!$showPasswordForm && !empty($_SESSION['reset_question'])): ?>
        <form method="POST">
            <div class="question-text">
                <?php echo $_SESSION['reset_question']; ?>
            </div>
            <div class="input-group">
                <input type="text" name="security_answer" placeholder="إجابة سؤال الأمان" required>
                <i class="fas fa-key"></i>
            </div>
            <button type="submit" class="btn">تحقق</button>
        </form>
        <?php endif; ?>
        
        <?php if ($showPasswordForm): ?>
        <form method="POST">
            <div class="input-group">
                <input type="password" name="new_password" placeholder="كلمة المرور الجديدة" required>
                <i class="fas fa-lock"></i>
            </div>
            <div class="input-group">
                <input type="password" name="confirm_password" placeholder="تأكيد كلمة المرور الجديدة" required>
                <i class="fas fa-lock"></i>
            </div>
            <button type="submit" class="btn">تحديث كلمة المرور</button>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>