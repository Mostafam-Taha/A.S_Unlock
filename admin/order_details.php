<?php
require_once '../includes/config.php';

session_start();

if (empty($_SESSION['admin_id'])) {
    header('Location: logs.php');
    exit;
}

// التحقق من وجود معرف الطلب
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: orders.php');
    exit();
}

$order_id = (int)$_GET['id'];

// استعلام معدل ليشمل معلومات المنتج الرقمي
$query = "SELECT o.*, u.name as user_name, u.email as user_email, u.phone as user_phone,
                 pl.plan_name, pl.plan_price, pl.plan_features,
                 dp.product_name, dp.description, dp.features, dp.price, dp.discount, 
                 dp.image_path, dp.created_at as product_created, dp.instructions
          FROM orders o
          JOIN users u ON o.user_id = u.id
          JOIN product_plans pl ON o.plan_id = pl.id
          JOIN digital_products dp ON o.product_id = dp.id
          WHERE o.id = :order_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// إذا لم يتم العثور على الطلب
if (!$order) {
    header('Location: orders.php');
    exit();
}

// معالجة تغيير الحالة إذا تم إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $new_status = $_POST['status'];
    $update_query = "UPDATE orders SET status = :status WHERE id = :order_id";
    $update_stmt = $pdo->prepare($update_query);
    $update_stmt->bindParam(':status', $new_status);
    $update_stmt->bindParam(':order_id', $order_id);
    $update_stmt->execute();
    
    // تحديث بيانات الطلب بعد التغيير
    $stmt->execute();
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
}

// تحويل الميزات من JSON إلى مصفوفة
$plan_features = json_decode($order['plan_features'], true);
$product_features = json_decode($order['features'], true);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الطلب #<?php echo $order['id']; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .order-container {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            max-width: 1200px;
            margin: 0 auto;
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .order-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
        }
        .status-pending {
            background-color: #FFF3E0;
            color: #FF9800;
        }
        .status-verified {
            background-color: #E3F2FD;
            color: #2196F3;
        }
        .status-completed {
            background-color: #E8F5E9;
            color: #4CAF50;
        }
        .status-rejected {
            background-color: #FFEBEE;
            color: #F44336;
        }
        .order-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .detail-card {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .detail-card h3 {
            margin-top: 0;
            color: #444;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .detail-row {
            display: flex;
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: bold;
            width: 120px;
            color: #666;
        }
        .detail-value {
            flex: 1;
        }
        .status-timeline {
            margin-top: 30px;
        }
        .timeline-steps {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-top: 20px;
        }
        .timeline-steps:before {
            content: "";
            position: absolute;
            top: 14px;
            left: 0;
            right: 0;
            height: 3px;
            background-color: #e0e0e0;
            z-index: 1;
        }
        .timeline-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
        }
        .step-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #e0e0e0;
            color: #999;
            margin-bottom: 5px;
        }
        .step-active .step-icon {
            background-color: #4CAF50;
            color: white;
        }
        .step-label {
            font-size: 12px;
            color: #999;
        }
        .step-active .step-label {
            color: #4CAF50;
            font-weight: bold;
        }
        .receipt-image {
            max-width: 100%;
            max-height: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-top: 10px;
        }
        .plan-features {
            margin-top: 15px;
        }
        .plan-features ul {
            padding-right: 20px;
        }
        .plan-features li {
            margin-bottom: 8px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        select {
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid #ddd;
            width: 100%;
            max-width: 300px;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
        /*  */
        /*  */
        /*  */
        .product-image {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            margin-top: 10px;
        }
        .discount-badge {
            background-color: #f44336;
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            margin-right: 5px;
        }
        .original-price {
            text-decoration: line-through;
            color: #999;
            margin-left: 5px;
        }
        /*  */
        /*  */
        /*  */
        .print-btn {
            background-color: #2196F3;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }
        
        .print-btn:hover {
            background-color: #0b7dda;
        }
        
        @media print {
            .no-print {
                display: none;
            }
            
            body {
                font-size: 12pt;
                background: none;
                color: #000;
            }
            
            .order-container {
                box-shadow: none;
                padding: 0;
                margin: 0;
                width: 100%;
            }
            
            .status-badge, .discount-badge {
                color: black !important;
                background-color: transparent !important;
                border: 1px solid #000;
            }
        }
    </style>
</head>
<body>
    <div class="order-container">
        <div class="order-header">
            <div class="order-title">تفاصيل الطلب #FD<?php echo str_pad($order['id'], 2, '0', STR_PAD_LEFT); ?></div>
            <div class="status-badge status-<?php echo $order['status']; ?>">
                <?php 
                switch ($order['status']) {
                    case 'pending':
                        echo 'قيد الانتظار';
                        break;
                    case 'verified':
                        echo 'تم التحقق';
                        break;
                    case 'completed':
                        echo 'مكتمل';
                        break;
                    case 'rejected':
                        echo 'مرفوض';
                        break;
                }
                ?>
            </div>
        </div>

        <div class="order-details">
            <div class="detail-card">
                <h3>معلومات العميل</h3>
                <div class="detail-row">
                    <div class="detail-label">الاسم:</div>
                    <div class="detail-value"><?php echo htmlspecialchars($order['user_name']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">البريد الإلكتروني:</div>
                    <div class="detail-value"><?php echo htmlspecialchars($order['user_email']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">رقم الهاتف:</div>
                    <div class="detail-value"><?php echo htmlspecialchars($order['phone_number']); ?></div>
                </div>
            </div>

            <div class="detail-card">
                <h3>معلومات المنتج</h3>
                <div class="detail-row">
                    <div class="detail-label">اسم المنتج:</div>
                    <div class="detail-value"><?php echo htmlspecialchars($order['product_name']); ?></div>
                </div>
                
                <?php if (!empty($order['description'])): ?>
                <div class="detail-row">
                    <div class="detail-label">الوصف:</div>
                    <div class="detail-value"><?php echo htmlspecialchars($order['description']); ?></div>
                </div>
                <?php endif; ?>
                
                <div class="detail-row">
                    <div class="detail-label">السعر:</div>
                    <div class="detail-value">
                        <?php if ($order['discount'] > 0): ?>
                            <span class="discount-badge">خصم <?php echo $order['discount']; ?>%</span>
                            <?php 
                            $discounted_price = $order['price'] * (1 - ($order['discount'] / 100));
                            echo number_format($discounted_price, 2); 
                            ?>
                            <span class="original-price"><?php echo number_format($order['price'], 2); ?></span>
                        <?php else: ?>
                            <?php echo number_format($order['price'], 2); ?>
                        <?php endif; ?>
                        جنيه
                    </div>
                </div>
                
                <?php if (!empty($order['image_path'])): ?>
                <div class="detail-row">
                    <div class="detail-label">صورة المنتج:</div>
                    <div class="detail-value">
                        <img src="<?php echo htmlspecialchars($order['image_path']); ?>" 
                            alt="<?php echo htmlspecialchars($order['product_name']); ?>" 
                            class="product-image">
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($product_features) && is_array($product_features)): ?>
                <div class="detail-row">
                    <div class="detail-label">ميزات المنتج:</div>
                    <div class="detail-value">
                        <ul>
                            <?php foreach ($product_features as $feature): ?>
                                <li><?php echo htmlspecialchars($feature); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($order['instructions'])): ?>
                <div class="detail-row">
                    <div class="detail-label">تعليمات الاستخدام:</div>
                    <div class="detail-value"><?php echo nl2br(htmlspecialchars($order['instructions'])); ?></div>
                </div>
                <?php endif; ?>
                
                <div class="detail-row">
                    <div class="detail-label">تاريخ الإضافة:</div>
                    <div class="detail-value"><?php echo date('Y-m-d H:i', strtotime($order['product_created'])); ?></div>
                </div>
            </div>
            
            <div class="detail-card">
                <h3>معلومات الطلب</h3>
                <div class="detail-row">
                    <div class="detail-label">الخطة:</div>
                    <div class="detail-value"><?php echo htmlspecialchars($order['plan_name']); ?></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">السعر:</div>
                    <div class="detail-value"><?php echo number_format($order['plan_price'], 2); ?> جنيه</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">طريقة الدفع:</div>
                    <div class="detail-value">
                        <?php 
                        if ($order['payment_method'] == 'vodafone_cash') {
                            echo 'فودافون كاش';
                        } elseif ($order['payment_method'] == 'instapay') {
                            echo 'انستاباي';
                        }
                        ?>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">تاريخ الطلب:</div>
                    <div class="detail-value"><?php echo date('Y-m-d H:i', strtotime($order['created_at'])); ?></div>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <h3>تغيير حالة الطلب</h3>
            <form method="post">
                <div class="form-group">
                    <select name="status" id="status">
                        <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>قيد الانتظار</option>
                        <option value="verified" <?php echo $order['status'] == 'verified' ? 'selected' : ''; ?>>تم التحقق</option>
                        <option value="completed" <?php echo $order['status'] == 'completed' ? 'selected' : ''; ?>>مكتمل</option>
                        <option value="rejected" <?php echo $order['status'] == 'rejected' ? 'selected' : ''; ?>>مرفوض</option>
                    </select>
                </div>
                <button type="submit" class="btn">حفظ التغييرات</button>
            </form>
        </div>

        <div class="status-timeline">
            <h3>حالة الطلب</h3>
            <div class="timeline-steps">
                <div class="timeline-step <?php echo in_array($order['status'], ['pending', 'verified', 'completed', 'rejected']) ? 'step-active' : ''; ?>">
                    <div class="step-icon">
                        <i class="bi bi-cart"></i>
                    </div>
                    <div class="step-label">تم الطلب</div>
                </div>
                <div class="timeline-step <?php echo in_array($order['status'], ['verified', 'completed', 'rejected']) ? 'step-active' : ''; ?>">
                    <div class="step-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="step-label">تم التحقق</div>
                </div>
                <div class="timeline-step <?php echo in_array($order['status'], ['completed']) ? 'step-active' : ''; ?>">
                    <div class="step-icon">
                        <i class="bi bi-check-all"></i>
                    </div>
                    <div class="step-label">مكتمل</div>
                </div>
                <div class="timeline-step <?php echo in_array($order['status'], ['rejected']) ? 'step-active' : ''; ?>">
                    <div class="step-icon">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div class="step-label">مرفوض</div>
                </div>
            </div>
        </div>

        <?php if (!empty($order['receipt_image'])): ?>
        <div class="detail-card">
            <h3>صورة الإيصال</h3>
            <img src="../uploads/receipts/<?php echo htmlspecialchars($order['receipt_image']); ?>" alt="صورة الإيصال" class="receipt-image">
        </div>
        <?php endif; ?>

        <div class="detail-card">
            <h3>تفاصيل الخطة</h3>
            <div class="plan-features">
                <?php if (!empty($plan_features) && is_array($plan_features)): ?>
                    <ul>
                        <?php foreach ($plan_features as $feature): ?>
                            <li><?php echo htmlspecialchars($feature); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>لا توجد ميزات محددة لهذه الخطة.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>