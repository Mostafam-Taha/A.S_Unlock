<?php
session_start();
require_once '../includes/check_maintenance.php';
require_once '../includes/config.php';
define('PROTECTED_ACCESS', true);
require_once '../includes/telegram_config.php';

// التحقق من أن الطلب قادم من نطاقات مسموحة
$allowed_domains = ['localhost', 'asunlock.ct.ws'];
$referer = parse_url($_SERVER['HTTP_REFERER'] ?? '', PHP_URL_HOST);
if (!in_array($referer, $allowed_domains)) {
    header('HTTP/1.1 403 Forbidden');
    exit('Access Denied');
}

if (!isset($_SESSION['order_success'])) {
    header('Location: ../client/download.php#download-staps');
    exit;
}

$order_data = $_SESSION['order_success'] ? $_SESSION['emailjs_data'] : null;

if ($order_data) {
    //sendOrderConfirmationEmail($order_data); // تم تعطيل إرسال البريد
    sendTelegramNotification($order_data);
}

unset($_SESSION['order_success']);
unset($_SESSION['emailjs_data']);

/**
 * إرسال إيميل التأكيد
 */
function sendOrderConfirmationEmail($order_data) {
    $to = $order_data['email'];
    $subject = "تأكيد طلبك #" . $order_data['order_id'];
    
    $message = "
    <html dir='rtl'>
    <head>
        <title>تأكيد الطلب</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background-color: #1976D2; color: white; padding: 10px; text-align: center; }
            .content { padding: 20px; }
            .footer { text-align: center; font-size: 12px; color: #777; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>تم استلام طلبك بنجاح!</h2>
            </div>
            <div class='content'>
                <p>مرحباً {$order_data['email']},</p>
                <p>شكراً لطلبك من A.S UNLOCK. إليك تفاصيل طلبك:</p>
                
                <h3>تفاصيل الطلب</h3>
                <p><strong>رقم الطلب:</strong> {$order_data['order_id']}</p>
                <p><strong>المنتج:</strong> {$order_data['product_name']}</p>
                <p><strong>الخطة:</strong> {$order_data['plan_name']}</p>
                <p><strong>السعر:</strong> {$order_data['plan_price']} جنيه</p>
                <p><strong>طريقة الدفع:</strong> {$order_data['payment_method']}</p>
                
                <p>سيتم مراجعة طلبك وإرسال تفاصيل التنشيط خلال 5-15 دقيقة.</p>
                <p>لأي استفسارات، لا تتردد في التواصل معنا.</p>
            </div>
            <div class='footer'>
                <p>A.S UNLOCK &copy; " . date('Y') . "</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: A.S UNLOCK <no-reply@asunlock.ct.ws>\r\n";
    $headers .= "Reply-To: support@asunlock.ct.ws\r\n";
    
    mail($to, $subject, $message, $headers);
}

/**
 * إرسال إشعار Telegram
 */
function sendTelegramNotification($order_data) {
    $telegramBotToken = '8403544536:AAHqqOWipI-PXZ0e3Ndy_H28x2gX50ldOeQ'; // يجب تخزين هذا في ملف إعدادات آمن
    $chatId = '@asorders';
    
    $orderLink = "https://asunlock.ct.ws/admin/order_details.php?id=" . $order_data['order_id'];
    
    $message = "
    🚀 طلب جديد!
    
    📦 المنتج: {$order_data['product_name']}
    📌 الخطة: {$order_data['plan_name']}
    💰 السعر: {$order_data['plan_price']} جنيه
    💳 طريقة الدفع: {$order_data['payment_method']}
    
    ----------------------------
    ----------------------------
    📞 الهاتف: {$order_data['phone_number']}
    ____
    📧 البريد: {$order_data['email']}
    ____
    
    🆔 رقم الطلب: {$order_data['order_id']}
    ____
    📅 تاريخ الطلب: {$order_data['order_date']}
    ____
    
    🔗 رابط تفاصيل الطلب: <a href=\"{$orderLink}\">عرض الطلب</a>
    ____
    
    " . ($order_data['receipt_image'] ? '📎 تم تحميل صورة الإيصال' : '') . "
    ";
    
    $url = "https://api.telegram.org/bot{$telegramBotToken}/sendMessage";
    $data = [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'HTML'
    ];
    
    $options = [
        'http' => [
            'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    
    $context = stream_context_create($options);
    file_get_contents($url, false, $context);

    
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأكيد الطلب</title>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- Links -->
    <link rel="icon" type="image/x-icon" href="../assets/image/favicon.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap"rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/checkout.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    }

    html, body {
        height: 100%;
        scroll-behavior: smooth;
    }

    body {
        font-family: "Tajawal", sans-serif;
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background-color: #f8f9fa;
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
        font-weight: var(--br-font-w-text);
    }

    a {
        text-decoration: none;
        color: var(--br-sacn-color);
    }

    .container {
        max-width: 800px;
        width: 90%;
        margin: auto;
        padding: 2rem;
        text-align: center;
    }

    .alert-success {
        background: var(--br-primary-color);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .alert-success h4 {
        color: white;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .alert-success p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.1rem;
    }

    .alert-success .bi-check-circle {
        font-size: 1.8rem;
        vertical-align: middle;
        margin-left: 0.5rem;
    }

    .card {
        border: none;
        border-radius: 8px;
        overflow: hidden;
        width: 100%;
        margin: 0 auto;
    }

    .card-header {
        background: var(--br-primary-color);
        padding: 1.2rem 1.5rem;
        font-weight: 600;
        font-size: 1.2rem;
    }

    .card-body {
        padding: 1.5rem;
        text-align: right;
    }

    .card-body p {
        margin-bottom: 0.8rem;
        font-size: 1rem;
        color: var(--br-sacn-color);
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid var(--br-border-color);
        padding-bottom: 0.5rem;
    }

    .card-body p:last-child {
        margin-bottom: 0;
        border-bottom: none;
    }

    .card-body p strong {
        font-weight: 600;
        color: var(--br-color-h-p);
    }

    @media (max-width: 768px) {
        .container {
            padding: 1rem;
        }
        
        .alert-success {
            padding: 1rem;
        }
        
        .card-body p {
            flex-direction: column;
            text-align: right;
        }
        
        .card-body p strong {
            margin-bottom: 0.3rem;
        }
    }
    .btn-primary {
        background: var(--br-primary-color);
        border: none;
        padding: var(--br-btn-padding);
        border-radius: 15px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        opacity: 0.9;
        box-shadow: var(--br-box-shadow);
    }

    .flex-contact {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .flex-contact .mt-3 {
        margin-top: 0;
    }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="alert alert-success text-center">
            <h4><i class="bi bi-check-circle"></i> تم تأكيد طلبك بنجاح!</h4>
            <p>سيتم مراجعة طلبك وإرسال تفاصيل التنشيط إلى بريدك الإلكتروني.</p>
            
            <!-- رسالة التأكيد الإضافية -->
            <div class="alert alert-info mt-3">
                <i class="bi bi-info-circle"></i> تم إرسال بياناتك بنجاح، وسيتم التواصل معك عبر الواتساب أو التليجرام خلال 5-15 دقيقة.
            </div>
        </div>
        
        <?php if ($order_data): ?>
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5>تفاصيل الطلب</h5>
            </div>
            <div class="card-body">
                <p><strong>رقم الطلب:</strong> <?= htmlspecialchars($order_data['order_id']) ?></p>
                <p><strong>المنتج:</strong> <?= htmlspecialchars($order_data['product_name']) ?></p>
                <p><strong>الخطة:</strong> <?= htmlspecialchars($order_data['plan_name']) ?></p>
                <p><strong>السعر:</strong> <?= htmlspecialchars($order_data['plan_price']) ?> جنيه</p>
                <p><strong>طريقة الدفع:</strong> <?= htmlspecialchars($order_data['payment_method']) ?></p>
                <p><strong>رقم الهاتف:</strong> <?= htmlspecialchars($order_data['phone_number']) ?></p>
                <p><strong>البريد الإلكتروني:</strong> <?= htmlspecialchars($order_data['email']) ?></p>
                
                <div class="flex-contact">
                    <a style="padding: 8px 22px; background: var(--br-primary-color); margin: 0 0 -5px 0; border-radius: 6px;" href="download.php">الخطوة التالية</a>
                    <!-- زر إلغاء الطلب -->
                    <button type="button" class="btn btn-danger mt-3" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                        <i class="bi bi-x-circle"></i> إلغاء الطلب
                    </button>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- نافذة إلغاء الطلب -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelOrderModalLabel">إلغاء الطلب</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="cancelOrderForm">
                        <div class="mb-3">
                            <label for="cancelReason" class="form-label">سبب الإلغاء</label>
                            <textarea class="form-control" id="cancelReason" rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-danger" onclick="submitCancelRequest('<?= $order_data['order_id'] ?>')">تأكيد الإلغاء</button>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/js/cancal_order.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- إضافة مكتبة EmailJS -->
    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
    <script src="../assets/js/order_confirmation.js"></script>
</body>
</html>