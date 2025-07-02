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
    <link rel="stylesheet" href="../assets/css/regs.css">

    <title>A.S UNLOCK - Admin</title>
</head>
<body>
    <div class="signup-container">
        <div class="signup-form-section">
            <div class="signup-header">
                <h2>إنشاء حساب جديد</h2>
                <p>املأ النموذج أدناه لإنشاء حسابك</p>
            </div>
            
            <form id="signupForm">
                <div class="input-group">
                    <input type="text" id="username" placeholder="اسم المستخدم (بالإنجليزية بدون مسافات)" required 
                           pattern="[a-zA-Z0-9]+" title="يجب أن يحتوي اسم المستخدم على أحرف إنجليزية وأرقام فقط">
                    <i class="fas fa-user"></i>
                    <span class="username-note">مثال: amogiz, user123 (بدون مسافات أو أحرف خاصة)</span>
                </div>
                
                <div class="input-group">
                    <input type="email" id="email" placeholder="البريد الإلكتروني" required>
                    <i class="fas fa-envelope"></i>
                </div>
                
                <div class="input-group">
                    <input type="password" id="password" placeholder="كلمة المرور" required 
                           oninput="checkPasswordStrength(this.value)">
                    <i class="fas fa-lock"></i>
                    <div class="password-strength">
                        <div class="password-strength-bar" id="passwordBar"></div>
                    </div>
                    <div class="password-strength-text" id="passwordText">قوة كلمة المرور: ضعيفة</div>
                </div>
                
                <div class="security-question">
                    <h4>سؤال الأمان</h4>
                    <div class="input-group">
                        <select id="securityQuestion" required>
                            <option value="" disabled selected>اختر سؤال الأمان</option>
                            <option value="1">ما اسم أول مدرسة التحقت بها؟</option>
                            <option value="2">ما هو اسم حي طفولتك؟</option>
                            <option value="3">ما هو اسم والدتك قبل الزواج؟</option>
                            <option value="4">ما هو اسم أول حيوان أليف امتلكته؟</option>
                            <option value="5">ما هو أفضل صديق في طفولتك؟</option>
                        </select>
                        <i class="fas fa-question-circle"></i>
                    </div>
                    
                    <div class="input-group">
                        <input type="text" id="securityAnswer" placeholder="إجابة سؤال الأمان" required>
                        <i class="fas fa-key"></i>
                    </div>
                </div>
                
                <button type="submit" class="signup-btn">إنشاء الحساب</button>
            </form>
            
            <div class="login-link">
                <p>هل لديك حساب بالفعل؟ <a href="logs.php">تسجيل الدخول</a></p>
            </div>
        </div>
        <div class="signup-image-section"></div>
    </div>
    <script src="../assets/js/logs_admin.js"></script>
</body>
</html>