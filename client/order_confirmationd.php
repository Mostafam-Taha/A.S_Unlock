<?php
session_start();
if (!isset($_SESSION['order_success'])) {
    header('Location: ../public/index.html');
    exit;
}

$order_data = $_SESSION['order_success'] ? $_SESSION['emailjs_data'] : null;
unset($_SESSION['order_success']);
unset($_SESSION['emailjs_data']);
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
    <meta name="description" content="A.S UNLOCK - خبراء فتح وإصلاح أجهزة التابلت بأحدث التقنيات. خدمات سريعة ومضمونة مع دعم على مدار الساعة." />

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
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap"rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/checkout.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="alert alert-success text-center">
            <h4><i class="bi bi-check-circle"></i> تم تأكيد طلبك بنجاح!</h4>
            <p>سيتم مراجعة طلبك وإرسال تفاصيل التنشيط إلى بريدك الإلكتروني.</p>
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
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- إضافة مكتبة EmailJS -->
    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
    <script src="../assets/js/dark-mode.js"></script>
    <script>
        // تهيئة EmailJS
        emailjs.init('OL-bmAupqH0hzZS0X');
        
        // إرسال البريد الإلكتروني عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($order_data): ?>
            const orderData = {
                order_id: '<?= $order_data["order_id"] ?>', // أضف هذا السطر
                order_link: '<?= $order_data["order_link"] ?>', // أضف هذا السطر
                product_name: '<?= $order_data["product_name"] ?>',
                plan_name: '<?= $order_data["plan_name"] ?>',
                plan_price: '<?= $order_data["plan_price"] ?>',
                payment_method: '<?= $order_data["payment_method"] ?>',
                phone_number: '<?= $order_data["phone_number"] ?>',
                email: '<?= $order_data["email"] ?>',
                receipt_image: '<?= $order_data["receipt_image"] ?>', // أضف هذا السطر إذا كنت بحاجه
                order_date: '<?= $order_data["order_date"] ?>',
                to_email: '<?= $order_data["email"] ?>',
                to_name: 'عميلنا العزيز'
            };
            
            emailjs.send('service_0v2rxhh', 'template_9qfoidp', orderData)
                .then(function(response) {
                    console.log('تم إرسال البريد الإلكتروني بنجاح!', response.status, response.text);
                }, function(error) {
                    console.error('فشل إرسال البريد الإلكتروني:', error);
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>