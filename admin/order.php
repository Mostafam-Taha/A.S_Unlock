<?php
require_once '../includes/config.php';

// الحصول على معرف الطلب من URL
$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($order_id <= 0) {
    header("Location: 404.php");
    exit;
}

// جلب بيانات الطلب من قاعدة البيانات
// بهذا (بإزالة product_description)
$stmt = $pdo->prepare("
    SELECT o.*, 
           p.product_name, 
           pl.plan_name,
           pl.plan_features
    FROM orders o
    LEFT JOIN digital_products p ON o.product_id = p.id
    LEFT JOIN product_plans pl ON o.plan_id = pl.id
    WHERE o.id = ?
");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header("Location: 404.php");
    exit;
}

// تحويل ميزات الخطة من JSON إلى مصفوفة
$features = json_decode($order['plan_features'], true) ?? [];
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الطلب #<?= $order_id ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f8f9fa;
        }
        .order-card {
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .receipt-img {
            max-height: 300px;
            object-fit: contain;
        }
        .feature-list {
            list-style-type: none;
            padding-right: 0;
        }
        .feature-list li {
            padding: 5px 0;
        }
        .feature-list li i {
            color: #0d6efd;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card order-card border-primary mb-4">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">
                            <i class="bi bi-receipt"></i> تفاصيل الطلب #<?= $order_id ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <!-- حالة الطلب -->
                        <div class="alert alert-<?= 
                            $order['status'] == 'completed' ? 'success' : 
                            ($order['status'] == 'rejected' ? 'danger' : 'info')
                        ?>">
                            <h5 class="alert-heading">
                                <?= 
                                    $order['status'] == 'completed' ? 'تم الانتهاء' : 
                                    ($order['status'] == 'rejected' ? 'مرفوض' : 'قيد المراجعة')
                                ?>
                            </h5>
                            <p class="mb-0">
                                <?= 
                                    $order['status'] == 'completed' ? 'تمت معالجة طلبك بنجاح' : 
                                    ($order['status'] == 'rejected' ? 'تم رفض الطلب، يرجى التواصل مع الدعم' : 
                                    'طلبك قيد المراجعة من قبل الفريق المختص')
                                ?>
                            </p>
                        </div>

                        <!-- معلومات المنتج -->
                        <div class="mb-4">
                            <h4 class="text-primary">
                                <i class="bi bi-box-seam"></i> معلومات المنتج
                            </h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>اسم المنتج:</strong> <?= htmlspecialchars($order['product_name']) ?></p>
                                    <p><strong>الخطة:</strong> <?= htmlspecialchars($order['plan_name']) ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>السعر:</strong> <?= htmlspecialchars($order['amount']) ?> جنيه</p>
                                    <p><strong>تاريخ الطلب:</strong> <?= date('Y-m-d H:i', strtotime($order['created_at'])) ?></p>
                                </div>
                            </div>
                            
                            <?php if (!empty($features)): ?>
                            <div class="mt-3">
                                <h5><i class="bi bi-list-check"></i> مميزات الخطة:</h5>
                                <ul class="feature-list">
                                    <?php foreach ($features as $feature): ?>
                                        <li><i class="bi bi-check-circle"></i> <?= htmlspecialchars($feature) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- معلومات الدفع -->
                        <div class="mb-4">
                            <h4 class="text-primary">
                                <i class="bi bi-credit-card"></i> معلومات الدفع
                            </h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>طريقة الدفع:</strong> 
                                        <?= $order['payment_method'] == 'vodafone_cash' ? 'فودافون كاش' : 'إنستاباي' ?>
                                    </p>
                                    <p><strong>رقم الهاتف:</strong> <?= htmlspecialchars($order['phone_number']) ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>البريد الإلكتروني:</strong> <?= htmlspecialchars($order['email']) ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- صورة الإيصال -->
                        <?php if (!empty($order['receipt_image'])): ?>
                        <div class="mb-4">
                            <h4 class="text-primary">
                                <i class="bi bi-image"></i> صورة الإيصال
                            </h4>
                            <div class="text-center">
                                <img src="../uploads/receipts/<?= htmlspecialchars($order['receipt_image']) ?>" 
                                     alt="صورة الإيصال" 
                                     class="img-fluid receipt-img border rounded">
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- معلومات إضافية -->
                        <div class="alert alert-info mt-4">
                            <h5><i class="bi bi-info-circle"></i> ملاحظات:</h5>
                            <p>يمكنك متابعة حالة الطلب من خلال هذه الصفحة باستخدام الرابط الذي تم إرساله إليك عبر البريد الإلكتروني.</p>
                            <p>لأي استفسارات، يرجى التواصل مع الدعم الفني عبر البريد الإلكتروني: support@a.s_unlock.ct.ws</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>