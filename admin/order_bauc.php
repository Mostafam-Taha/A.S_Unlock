<?php
require_once '../INCLUDES/CONFIG.PHP';

// التحقق من وجود معرف الطلب
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = (int)$_GET['id'];

// معالجة تحديث الحالة إذا تم إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $new_status = $_POST['status'];
    
    try {
        $update_stmt = $pdo->prepare("UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?");
        $update_stmt->execute([$new_status, $order_id]);
        
        // رسالة نجاح
        $success_message = "تم تحديث حالة الطلب بنجاح";
        
        // إعادة جلب البيانات بعد التحديث
        $stmt = $pdo->prepare("SELECT o.*, u.name as user_name FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE o.id = ?");
        $stmt->execute([$order_id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        
    } catch (Exception $e) {
        $error_message = "حدث خطأ أثناء تحديث الحالة: " . $e->getMessage();
    }
}

try {
    // جلب بيانات الطلب من قاعدة البيانات
    $stmt = $pdo->prepare("
        SELECT o.*, u.name as user_name 
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        WHERE o.id = ?
    ");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        throw new Exception("الطلب غير موجود");
    }

} catch (Exception $e) {
    die("حدث خطأ: " . $e->getMessage());
}

// تحويل حالة الطلب إلى نص عربي
$status_text = '';
switch ($order['status']) {
    case 'pending':
        $status_text = 'قيد الانتظار';
        break;
    case 'verified':
        $status_text = 'تم التحقق';
        break;
    case 'completed':
        $status_text = 'مكتمل';
        break;
    case 'rejected':
        $status_text = 'مرفوض';
        break;
}

// تحويل طريقة الدفع إلى نص عربي
$payment_method_text = $order['payment_method'] == 'vodafone_cash' ? 'فودافون كاش' : 'انستاباي';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الطلب #<?php echo $order_id; ?></title>
    <!-- Bootstrap RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <!-- Links -->
    <link rel="icon" type="image/x-icon" href="../assets/image/favicon.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Tajawal", sans-serif;
            background-color: #f8f9fa;
        }
        .status-badge {
            font-size: 0.9rem;
            padding: 0.35rem 0.65rem;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-verified { background-color: #cce5ff; color: #004085; }
        .status-completed { background-color: #d4edda; color: #155724; }
        .status-rejected { background-color: #f8d7da; color: #721c24; }
        .receipt-img {
            max-width: 100%;
            height: auto;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }
        .detail-card {
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col">
                <h1 class="h3 mb-0">
                    <i class="fas fa-file-invoice me-2"></i>
                    تفاصيل الطلب #FD<?php echo str_pad($order_id, 2, '0', STR_PAD_LEFT); ?>
                </h1>
            </div>
            <div class="col-auto">
                <a href="orders.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-right me-1"></i> العودة للقائمة
                </a>
            </div>
        </div>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8">
                <div class="card detail-card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>معلومات الطلب
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th width="30%">اسم المستخدم:</th>
                                        <td>
                                            <a href="users.php?user_id=<?php echo $order['user_id']; ?>" class="text-decoration-none">
                                                <?php echo htmlspecialchars($order['user_name']); ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>الباقة:</th>
                                        <td><?php echo htmlspecialchars($order['plan_name']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>سعر الباقة:</th>
                                        <td><?php echo number_format($order['plan_price'], 2); ?> جنيه</td>
                                    </tr>
                                    <tr>
                                        <th>المبلغ المدفوع:</th>
                                        <td><?php echo number_format($order['amount'], 2); ?> جنيه</td>
                                    </tr>
                                    <tr>
                                        <th>طريقة الدفع:</th>
                                        <td><?php echo $payment_method_text; ?></td>
                                    </tr>
                                    <tr>
                                        <th>رقم الهاتف:</th>
                                        <td><?php echo htmlspecialchars($order['phone_number']); ?></td>
                                    </tr>
                                    <tr>
                                        <th>البريد الإلكتروني:</th>
                                        <td><?php echo htmlspecialchars($order['email']); ?></td>
                                    </tr>
                                    <?php if (!empty($order['subscription_email'])): ?>
                                    <tr>
                                        <th>بريد الاشتراك:</th>
                                        <td><?php echo htmlspecialchars($order['subscription_email']); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <th>حالة الطلب:</th>
                                        <td>
                                            <span class="badge status-badge status-<?php echo $order['status']; ?>">
                                                <?php echo $status_text; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>تاريخ الإنشاء:</th>
                                        <td><?php echo date('Y-m-d H:i', strtotime($order['created_at'])); ?></td>
                                    </tr>
                                    <?php if (!empty($order['updated_at'])): ?>
                                    <tr>
                                        <th>تاريخ التحديث:</th>
                                        <td><?php echo date('Y-m-d H:i', strtotime($order['updated_at'])); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <?php if (!empty($order['receipt_image'])): ?>
                <div class="card detail-card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-receipt me-2"></i>صورة الإيصال
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <img src="<?php echo htmlspecialchars($order['receipt_image']); ?>" alt="صورة الإيصال" class="receipt-img img-fluid">
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-4">
                <div class="card detail-card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-sync-alt me-2"></i>تحديث الحالة
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="status" class="form-label">الحالة الحالية</label>
                                <input type="text" class="form-control" value="<?php echo $status_text; ?>" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">تغيير إلى</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>قيد الانتظار</option>
                                    <option value="verified" <?php echo $order['status'] == 'verified' ? 'selected' : ''; ?>>تم التحقق</option>
                                    <option value="completed" <?php echo $order['status'] == 'completed' ? 'selected' : ''; ?>>مكتمل</option>
                                    <option value="rejected" <?php echo $order['status'] == 'rejected' ? 'selected' : ''; ?>>مرفوض</option>
                                </select>
                            </div>
                            <button type="submit" name="update_status" class="btn btn-primary w-100">
                                <i class="fas fa-save me-1"></i> حفظ التغييرات
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card detail-card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-cog me-2"></i>الإجراءات
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="#" class="btn btn-outline-secondary">
                                <i class="fas fa-envelope me-1"></i> إرسال إشعار
                            </a>
                            <a href="#" class="btn btn-outline-danger">
                                <i class="fas fa-trash-alt me-1"></i> حذف الطلب
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>