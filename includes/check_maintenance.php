<?php
require_once 'config.php';

// جلب إعدادات الصيانة
$stmt = $pdo->query("SELECT * FROM maintenance_settings WHERE id = 1");
$maintenance = $stmt->fetch(PDO::FETCH_ASSOC);

// التحقق من IP المسموح به
$allowed_ips = explode(',', $maintenance['allowed_ips']);
$allowed_ips = array_map('trim', $allowed_ips);
$client_ip = $_SERVER['REMOTE_ADDR'];

// إذا كان وضع الصيانة نشطًا ولم يكن المستخدم مسجل دخول أو IP غير مسموح
if ($maintenance['is_active'] && !in_array($client_ip, $allowed_ips) && !isset($_SESSION['admin_id'])) {
    // إذا كان هناك وقت محدد للصيانة
    if ($maintenance['start_time'] && $maintenance['end_time']) {
        $now = new DateTime();
        $start = new DateTime($maintenance['start_time']);
        $end = new DateTime($maintenance['end_time']);
        
        // إذا كنا في نطاق وقت الصيانة
        if ($now >= $start && $now <= $end) {
            showMaintenancePage($maintenance['message']);
        }
    } else {
        // إذا لم يكن هناك وقت محدد (صيانة دائمة)
        showMaintenancePage($maintenance['message']);
    }
}

function showMaintenancePage($message) {
    http_response_code(503);
    header('Retry-After: 3600'); // إعادة المحاولة بعد ساعة
    
    // تصميم صفحة الصيانة
    echo '<!DOCTYPE html>
    <html lang="ar" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>الموقع قيد الصيانة</title>
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            body {
                font-family: "Tajawal", sans-serif;
                background: #f8fafc;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                padding: 20px;
                text-align: center;
                color: #334155;
            }
            
            .maintenance-container {
                max-width: 600px;
                padding: 40px;
                background: white;
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            }
            
            h1 {
                font-size: 2.5rem;
                color: #1976D2;
                margin-bottom: 20px;
            }
            
            p {
                font-size: 1.2rem;
                line-height: 1.6;
                margin-bottom: 30px;
            }
            
            .icon {
                font-size: 4rem;
                color: #1976D2;
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <div class="maintenance-container">
            <div class="icon">
                <i class="fas fa-tools"></i>
            </div>
            <h1>الموقع قيد الصيانة</h1>
            <p>'. nl2br(htmlspecialchars($message)) .'</p>
        </div>
    </body>
    </html>';
    exit();
}