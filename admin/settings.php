<?php
require_once '../includes/config.php';

// التحقق من الصلاحيات
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// جلب إعدادات الصيانة الحالية
$stmt = $pdo->query("SELECT * FROM maintenance_settings WHERE id = 1");
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

// معالجة تحديث الإعدادات
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $message = $_POST['message'] ?? '';
    $allowed_ips = $_POST['allowed_ips'] ?? '';
    $start_time = $_POST['start_time'] ?? null;
    $end_time = $_POST['end_time'] ?? null;

    $stmt = $pdo->prepare("UPDATE maintenance_settings SET 
        is_active = ?, 
        message = ?, 
        allowed_ips = ?, 
        start_time = ?, 
        end_time = ? 
        WHERE id = 1");
    
    $stmt->execute([$is_active, $message, $allowed_ips, $start_time, $end_time]);
    
    $_SESSION['success_message'] = "تم تحديث إعدادات الصيانة بنجاح";
    header("Location: settings.php");
    exit();
}

?>
<?php
require_once '../includes/config.php';

// تفعيل عرض الأخطاء
ini_set('display_errors', 1);
error_reporting(E_ALL);

// التحقق من تسجيل الدخول
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$adminId = $_SESSION['admin_id'];

// جلب بيانات المشرف الحالي
try {
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE admin_id = ?");
    $stmt->execute([$adminId]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$admin) {
        die("المشرف غير موجود");
    }
} catch (PDOException $e) {
    die("خطأ في جلب البيانات: " . $e->getMessage());
}

// معالجة تحديث البيانات
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    
    // التحقق من الحقول المطلوبة
    if (empty($name) || empty($email)) {
        $_SESSION['error_message'] = "الاسم والبريد الإلكتروني مطلوبان";
        header("Location: profile.php");
        exit();
    }
    
    // معالجة رفع الصورة
    $profileImage = $admin['profile_image']; // الاحتفاظ بالقيمة الحالية
    
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/profiles/';
        if (!file_exists($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                $_SESSION['error_message'] = "تعذر إنشاء مجلد التحميل";
                header("Location: profile.php");
                exit();
            }
        }
        
        $fileExt = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($fileExt, $allowedExtensions)) {
            $fileName = 'admin_' . $adminId . '_' . time() . '.' . $fileExt;
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadPath)) {
                // حذف الصورة القديمة إذا كانت موجودة
                if (!empty($admin['profile_image']) && file_exists($uploadDir . $admin['profile_image'])) {
                    unlink($uploadDir . $admin['profile_image']);
                }
                $profileImage = $fileName;
            } else {
                $_SESSION['error_message'] = "فشل في تحميل الصورة";
                header("Location: profile.php");
                exit();
            }
        } else {
            $_SESSION['error_message'] = "نوع الملف غير مسموح به";
            header("Location: profile.php");
            exit();
        }
    }
    
    // تحديث البيانات في قاعدة البيانات
    try {
        $stmt = $pdo->prepare("UPDATE admins SET username = ?, email = ?, profile_image = ? WHERE admin_id = ?");
        $result = $stmt->execute([$name, $email, $profileImage, $adminId]);
        
        if ($result && $stmt->rowCount() > 0) {
            $_SESSION['success_message'] = "تم تحديث البيانات بنجاح";
            // تحديث بيانات الجلسة إذا لزم الأمر
            $_SESSION['admin_name'] = $name;
        } else {
            $_SESSION['error_message'] = "لم يتم تحديث أي بيانات، ربما لم تقم بتغيير أي شيء";
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "خطأ في قاعدة البيانات: " . $e->getMessage();
    }
    
    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صفحة الإعدادات</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
}

* {
    margin: 0;
    padding: 0;
    font-family: "Tajawal", sans-serif;
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}

body {
    font-family: "Tajawal", sans-serif;
    margin: 0;
    padding: 0;
    min-height: 100vh;
    background-color: #f5f7fa;
    color: var(--br-sacn-color);
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

.settings-container {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.settings-sidebar {
    width: 280px;
    background: white;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
    position: fixed;
    top: 0;
    right: 0;
    height: 100vh;
    overflow-y: auto;
    z-index: 90;
}

.sidebar-header {
    padding: 25px;
    border-bottom: 1px solid var(--br-border-color);
    text-align: center;
}

.sidebar-header h2 {
    font-size: 1.5rem;
    font-weight: 600;
    background: var(--br-primary-color);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.sidebar-menu {
    padding: 15px 0;
}

.menu-item {
    display: flex;
    align-items: center;
    padding: 14px 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    border-right: 3px solid transparent;
    margin: 5px 0;
}

.menu-item:hover {
    background-color: rgba(25, 118, 210, 0.05);
}

.menu-item.active {
    background-color: rgba(25, 118, 210, 0.1);
    border-right: 3px solid var(--br-color-h-p);
}

.menu-item i {
    margin-left: 10px;
    font-size: 1.1rem;
    color: var(--br-color-h-p);
}

.menu-item span {
    font-size: 0.95rem;
    font-weight: 500;
}

.settings-content {
    flex: 1;
    padding: 30px;
    margin-right: 280px;
}

.content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.content-header h1 {
    font-size: 1.8rem;
    font-weight: 600;
}

.content-section {
    background: white;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
    margin-bottom: 25px;
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.section-title {
    font-size: 1.3rem;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--br-border-color);
    display: flex;
    align-items: center;
}

.section-title i {
    margin-left: 10px;
    color: var(--br-color-h-p);
}

.profile-info {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.profile-img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    margin-left: 20px;
    border: 3px solid var(--br-border-color);
}

.profile-details h3 {
    font-size: 1.2rem;
    margin-bottom: 5px;
}

.profile-details p {
    color: var(--br-links-color);
    font-size: 0.9rem;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: var(--br-sacn-color);
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid var(--br-border-color);
    border-radius: 6px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: var(--br-color-h-p);
    box-shadow: var(--br-box-shadow);
}

.btn {
    padding: var(--br-btn-padding);
    border-radius: 6px;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
}

.btn-primary {
    background: var(--br-primary-color);
    color: white;
}

.btn-primary:hover {
    opacity: 0.9;
    transform: translateY(-2px);
}

.btn-danger {
    background: #f44336;
    color: white;
}

.btn-danger:hover {
    background: #e53935;
}

.maintenance-status {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-left: 10px;
}

.status-active {
    background-color: #4CAF50;
}

.status-inactive {
    background-color: #F44336;
}

.delete-warning {
    background-color: #FFF3E0;
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
}

.delete-warning i {
    color: #FFA000;
    margin-left: 5px;
}

/* إضافات جديدة للصيانة */
.switch-container {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
    margin-left: 10px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 34px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: #2196F3;
}

input:checked + .slider:before {
    transform: translateX(26px);
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.alert-success {
    background-color: #dff0d8;
    color: #3c763d;
    border: 1px solid #d6e9c6;
}

.action-buttons {
    margin-top: 20px;
}

@media (max-width: 992px) {
    .settings-sidebar {
        width: 100%;
        height: auto;
        position: static;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }
    
    .sidebar-menu {
        display: flex;
        overflow-x: auto;
        padding: 0;
        white-space: nowrap;
    }
    
    .menu-item {
        display: inline-flex;
        border-right: none;
        border-bottom: 3px solid transparent;
        margin: 0;
    }
    
    .menu-item.active {
        border-right: none;
        border-bottom: 3px solid var(--br-color-h-p);
    }
    
    .settings-content {
        margin-right: 0;
        padding: 20px;
    }
}

@media (max-width: 576px) {
    .settings-content {
        padding: 15px;
    }
    
    .content-header h1 {
        font-size: 1.5rem;
    }
    
    .content-section {
        padding: 20px;
    }
    
    .profile-info {
        flex-direction: column;
        text-align: center;
    }
    
    .profile-img {
        margin-left: 0;
        margin-bottom: 15px;
    }
    
    .form-control {
        padding: 10px 12px;
    }
}
    </style>
</head>
<body>
    <div class="settings-container">
        
        <div class="settings-sidebar" id="settingsSidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-cog"></i> الإعدادات</h2>
            </div>
            <div class="sidebar-menu">
                <div class="menu-item active" data-section="profile">
                    <i class="fas fa-user"></i>
                    <span>البروفايل</span>
                </div>
                <div class="menu-item" data-section="maintenance">
                    <i class="fas fa-tools"></i>
                    <span>صيانة في الموقع</span>
                </div>
                <div class="menu-item" data-section="delete">
                    <i class="fas fa-trash"></i>
                    <span>حذف البيانات</span>
                </div>
            </div>
        </div>

        <div class="settings-content">
            <div class="content-section" id="profile-section">
                <div class="content-header">
                    <h1><i class="fas fa-user"></i> إعدادات البروفايل</h1>
                </div>

                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
                <?php endif; ?>

                <div class="profile-info">
                    <img src="<?php echo $admin['profile_image'] ? '../uploads/profiles/' . $admin['profile_image'] : 'https://via.placeholder.com/80'; ?>" 
                        alt="صورة البروفايل" class="profile-img">
                    <div class="profile-details">
                        <h3><?php echo htmlspecialchars($admin['username']); ?></h3>
                        <p><?php echo htmlspecialchars($admin['email']); ?></p>
                    </div>
                </div>

                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">الاسم الكامل</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($admin['username']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">البريد الإلكتروني</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($admin['email']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="phone">رقم الهاتف</label>
                        <input type="tel" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($admin['phone'] ?? ''); ?>">
                    </div>

                    <div class="form-group">
                        <label for="profile_image">صورة البروفايل</label>
                        <input type="file" id="profile_image" name="profile_image" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </form>
            </div>

            <div class="content-section" id="maintenance-section" style="display: none;">
                <div class="content-header">
                    <h1><i class="fas fa-tools"></i> صيانة في الموقع</h1>
                    <p>إدارة وضع الصيانة للموقع بالكامل</p>
                </div>
                
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success">
                        <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                    </div>
                <?php endif; ?>
                
                <div class="maintenance-form">
                    <form method="POST">
                        <div class="form-group">
                            <div class="switch-container">
                                <label class="switch">
                                    <input type="checkbox" name="is_active" <?php echo $settings['is_active'] ? 'checked' : ''; ?>>
                                    <span class="slider"></span>
                                </label>
                                <span>وضع الصيانة <?php echo $settings['is_active'] ? 'مفعل' : 'معطل'; ?></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">رسالة الصيانة</label>
                            <textarea name="message" class="form-control" required><?php echo htmlspecialchars($settings['message'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="start_time">وقت البدء (اختياري)</label>
                            <input type="datetime-local" name="start_time" class="form-control" value="<?php echo $settings['start_time'] ? date('Y-m-d\TH:i', strtotime($settings['start_time'])) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="end_time">وقت الانتهاء (اختياري)</label>
                            <input type="datetime-local" name="end_time" class="form-control" value="<?php echo $settings['end_time'] ? date('Y-m-d\TH:i', strtotime($settings['end_time'])) : ''; ?>">
                        </div>
                        
                        <div class="action-buttons">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ الإعدادات
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="content-section" id="delete-section" style="display: none;">
                <div class="content-header">
                    <h1><i class="fas fa-trash"></i> حذف البيانات</h1>
                </div>

                <div class="delete-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>تحذير: حذف البيانات لا يمكن التراجع عنه. يرجى التأكد قبل المتابعة.</span>
                </div>

                <div class="form-group">
                    <label>اختر البيانات المراد حذفها</label>
                    <div style="margin-top: 10px;">
                        <label style="display: block; margin-bottom: 10px; cursor: pointer;">
                            <input type="checkbox"> سجل النشاطات
                        </label>
                        <label style="display: block; margin-bottom: 10px; cursor: pointer;">
                            <input type="checkbox"> الملفات المؤقتة
                        </label>
                        <label style="display: block; margin-bottom: 10px; cursor: pointer;">
                            <input type="checkbox"> سجل الدخول
                        </label>
                        <label style="display: block; cursor: pointer;">
                            <input type="checkbox"> جميع البيانات (حذف نهائي)
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="delete-confirm">اكتب "حذف" للتأكيد</label>
                    <input type="text" id="delete-confirm" class="form-control" placeholder="حذف">
                </div>

                <button class="btn btn-danger">حذف البيانات</button>
            </div>
        </div>
    </div>

    <script>
        function showSection(sectionId) {
            document.querySelectorAll('.content-section').forEach(section => {
                section.style.display = 'none';
            });
            
        
            document.getElementById(sectionId + '-section').style.display = 'block';
            
        
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
                if (item.getAttribute('data-section') === sectionId) {
                    item.classList.add('active');
                }
            });
            
    
            document.getElementById('mobile-menu-select').value = sectionId;
            
        
            if (window.innerWidth <= 992) {
                document.getElementById('settingsSidebar').classList.remove('active');
            }
        }

    
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function() {
                const sectionId = this.getAttribute('data-section');
                showSection(sectionId);
            });
        });

         
        
        document.getElementById('mobile-menu-select').addEventListener('change', function() {
            showSection(this.value);
        });

    
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('settingsSidebar').classList.toggle('active');
        });

    
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('settingsSidebar');
            const menuToggle = document.getElementById('menuToggle');
            
            if (window.innerWidth <= 992 && 
                !sidebar.contains(event.target) && 
                !menuToggle.contains(event.target)) {
                sidebar.classList.remove('active');
            }
        });

    
        document.addEventListener('DOMContentLoaded', function() {
            showSection('profile');
        });

    
        window.addEventListener('resize', function() {
            if (window.innerWidth > 992) {
                document.getElementById('settingsSidebar').classList.remove('active');
            }
        });
    </script>
</body>
</html>