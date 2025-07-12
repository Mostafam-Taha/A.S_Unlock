<?php
session_start();
require_once '../includes/config.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit;
}

// جلب معرّفات المنتج والخطة من URL
$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
$plan_id = isset($_GET['plan_id']) ? (int)$_GET['plan_id'] : 0;
$current_step = isset($_GET['step']) ? (int)$_GET['step'] : 1;

// جلب بيانات المستخدم
$user_id = $_SESSION['user_id'];
$user_stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$user_stmt->execute([$user_id]);
$user = $user_stmt->fetch(PDO::FETCH_ASSOC);

// جلب بيانات المنتج
$product = [];
$product_stmt = $pdo->prepare("SELECT * FROM digital_products WHERE id = ?");
$product_stmt->execute([$product_id]);
$product = $product_stmt->fetch(PDO::FETCH_ASSOC);

// جلب بيانات الخطة
$plan = [];
$plan_stmt = $pdo->prepare("SELECT * FROM product_plans WHERE id = ? AND product_id = ?");
$plan_stmt->execute([$plan_id, $product_id]);
$plan = $plan_stmt->fetch(PDO::FETCH_ASSOC);

// إذا لم يتم العثور على المنتج أو الخطة
if (empty($product) || empty($plan)) {
    header("Location: 404.php");
    exit;
}

// تحويل ميزات الخطة من JSON إلى مصفوفة
$plan_features = json_decode($plan['plan_features'], true);

// معالجة إرسال النموذج حسب الخطوة الحالية
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($current_step) {
        case 1:
            // التحقق من طريقة الدفع
            if (!isset($_POST['payment_method'])) {
                $errors[] = 'يجب اختيار طريقة الدفع';
            } else {
                $_SESSION['checkout_data']['payment_method'] = $_POST['payment_method'];
                $current_step = 2;
                header("Location: checkout.php?product_id=$product_id&plan_id=$plan_id&step=2");
                exit;
            }
            break;
            
        case 2:
            // التحقق من بيانات الاتصال
            $phone_number = $_POST['phone_number'] ?? '';
            $email = $_POST['email'] ?? '';
            
            if (empty($phone_number) || !preg_match('/^01[0-9]{9}$/', $phone_number)) {
                $errors[] = 'رقم الهاتف غير صالح';
            }
            
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'البريد الإلكتروني غير صالح';
            }
            
            if (empty($errors)) {
                $_SESSION['checkout_data']['phone_number'] = $phone_number;
                $_SESSION['checkout_data']['email'] = $email;
                $current_step = 3;
                header("Location: checkout.php?product_id=$product_id&plan_id=$plan_id&step=3");
                exit;
            }
            break;
            
        case 3:
            // التحقق من صورة الإيصال
            if (isset($_FILES['receipt_image']) && $_FILES['receipt_image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../uploads/receipts/';
                $file_name = time() . '_' . basename($_FILES['receipt_image']['name']);
                $target_path = $upload_dir . $file_name;
                
                $imageFileType = strtolower(pathinfo($target_path, PATHINFO_EXTENSION));
                $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
                
                if (in_array($imageFileType, $allowed_types)) {
                    if (move_uploaded_file($_FILES['receipt_image']['tmp_name'], $target_path)) {
                        $_SESSION['checkout_data']['receipt_image'] = $file_name;
                        $current_step = 4;
                        header("Location: checkout.php?product_id=$product_id&plan_id=$plan_id&step=4");
                        exit;
                    } else {
                        $errors[] = 'حدث خطأ أثناء رفع صورة الإيصال';
                    }
                } else {
                    $errors[] = 'نوع الملف غير مسموح به. يرجى رفع صورة (JPG, JPEG, PNG, GIF)';
                }
            } else {
                $errors[] = 'يجب رفع صورة الإيصال';
            }
            break;
            
        case 4:
            // تأكيد الطلب النهائي وحفظه في قاعدة البيانات
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO orders 
                    (user_id, product_id, plan_id, payment_method, phone_number, email, receipt_image, amount) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $user_id,
                    $product_id,
                    $plan_id,
                    $_SESSION['checkout_data']['payment_method'],
                    $_SESSION['checkout_data']['phone_number'],
                    $_SESSION['checkout_data']['email'],
                    $_SESSION['checkout_data']['receipt_image'],
                    $plan['plan_price']
                ]);
                
                // إرسال البريد الإلكتروني عبر EmailJS
                // بعد إدراج الطلب في قاعدة البيانات
                $order_id = $pdo->lastInsertId(); // الحصول على آخر ID تم إدراجه

                // إرسال البريد الإلكتروني عبر EmailJS
                $order_data = [
                    'order_id' => $order_id,
                    'order_link' => 'https://a.s-unlock.ct.ws/admin/order.php?id=' . $order_id,
                    'product_name' => $product['product_name'],
                    'plan_name' => $plan['plan_name'],
                    'plan_price' => $plan['plan_price'],
                    'payment_method' => $_SESSION['checkout_data']['payment_method'] == 'vodafone_cash' ? 'فودافون كاش' : 'إنستاباي',
                    'phone_number' => $_SESSION['checkout_data']['phone_number'],
                    'email' => $_SESSION['checkout_data']['email'],
                    'receipt_image' => '../uploads/receipts/' . $_SESSION['checkout_data']['receipt_image'],
                    'order_date' => date('Y-m-d H:i:s')
                ];
                
                // مسح بيانات الجلسة بعد الحفظ
                unset($_SESSION['checkout_data']);
                
                // إرسال رسالة نجاح
                $_SESSION['order_success'] = true;
                $_SESSION['emailjs_data'] = $order_data; // تخزين البيانات للإرسال عبر JavaScript
                
                header('Location: ../client/order_confirmation.php');
                exit;
            } catch (PDOException $e) {
                $errors[] = 'حدث خطأ في قاعدة البيانات: ' . $e->getMessage();
            }
            break;
    }
}










if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$stmt = $pdo->prepare("SELECT banned FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if ($user && $user['banned']) {
    session_unset();
    session_destroy();
    
    $_SESSION['login_error'] = 'تم حظر حسابك. الرجاء التواصل مع الإدارة.';
    header('Location: login.php');
    exit();
}
?>
?>

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
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap"rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/checkout.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>إتمام الطلب - <?= htmlspecialchars($product['product_name']) ?> - <?= htmlspecialchars($plan['plan_name']) ?></title>
    <style>
        /* إضافة أنماط لخطوات الدفع */
        .step-progress {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }
        .step-progress::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 0;
            right: 0;
            height: 2px;
            background: #dee2e6;
            z-index: 1;
        }
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
        }
        .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #dee2e6;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .step.active .step-number {
            background: #0d6efd;
            color: white;
        }
        .step.completed .step-number {
            background: #198754;
            color: white;
        }
        .step-label {
            font-size: 14px;
            color: #6c757d;
        }
        .step.active .step-label {
            color: #0d6efd;
            font-weight: bold;
        }
        .step.completed .step-label {
            color: #198754;
        }
        
        /* إخفاء الخطوات غير النشطة */
        .step-content {
            display: none;
        }
        .step-content.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="checkout-container p-4">
                    <h2 class="text-center mb-4"><i class="bi bi-cart-check"></i> إتمام الطلب</h2>
                    
                    <!-- شريط تقدم الخطوات -->
                    <div class="step-progress">
                        <div class="step <?= $current_step >= 1 ? 'active' : '' ?> <?= $current_step > 1 ? 'completed' : '' ?>">
                            <div class="step-number">1</div>
                            <div class="step-label">طريقة الدفع</div>
                        </div>
                        <div class="step <?= $current_step >= 2 ? 'active' : '' ?> <?= $current_step > 2 ? 'completed' : '' ?>">
                            <div class="step-number">2</div>
                            <div class="step-label">بيانات الاتصال</div>
                        </div>
                        <div class="step <?= $current_step >= 3 ? 'active' : '' ?> <?= $current_step > 3 ? 'completed' : '' ?>">
                            <div class="step-number">3</div>
                            <div class="step-label">إثبات الدفع</div>
                        </div>
                        <div class="step <?= $current_step >= 4 ? 'active' : '' ?>">
                            <div class="step-number">4</div>
                            <div class="step-label">تأكيد الطلب</div>
                        </div>
                    </div>
                    
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <!-- ملخص الطلب -->
                    <div class="card border-primary mb-4">
                        <div class="card-header bg-primary text-white">
                            <h4><i class="bi bi-receipt"></i> ملخص الطلب</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>المنتج:</strong> <?= htmlspecialchars($product['product_name']) ?></p>
                                    <p><strong>الخطة:</strong> <?= htmlspecialchars($plan['plan_name']) ?></p>
                                </div>
                                <div class="col-md-6 text-end">
                                    <p class="total-price">المجموع: <?= htmlspecialchars($plan['plan_price']) ?> جنيه</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- الخطوة 1: اختيار طريقة الدفع -->
                    <form id="payment-form" method="POST" enctype="multipart/form-data" class="step-content <?= $current_step == 1 ? 'active' : '' ?>">
                        <input type="hidden" name="step" value="1">
                        
                        <h4 class="mb-3"><i class="bi bi-credit-card"></i> طريقة الدفع</h4>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="payment-method" onclick="selectPaymentMethod('vodafone_cash')">
                                    <input type="radio" id="vodafone_cash" name="payment_method" value="vodafone_cash" class="d-none" 
                                        <?= isset($_SESSION['checkout_data']['payment_method']) && $_SESSION['checkout_data']['payment_method'] == 'vodafone_cash' ? 'checked' : '' ?> required>
                                    <div class="d-flex align-items-center">
                                        <img src="../assets/image/vodafone-cash.png" alt="فودافون كاش">
                                        <div>
                                            <h5 class="mb-1">فودافون كاش</h5>
                                            <p class="mb-0 text-muted small">الدفع عبر محفظة فودافون كاش</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="payment-method" onclick="selectPaymentMethod('instapay')">
                                    <input type="radio" id="instapay" name="payment_method" value="instapay" class="d-none"
                                        <?= isset($_SESSION['checkout_data']['payment_method']) && $_SESSION['checkout_data']['payment_method'] == 'instapay' ? 'checked' : '' ?>>
                                    <div class="d-flex align-items-center">
                                        <img src="../assets/image/instapay.jfif" alt="إنستاباي">
                                        <div>
                                            <h5 class="mb-1">إنستاباي</h5>
                                            <p class="mb-0 text-muted small">الدفع عبر إنستاباي</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="product.php?id=<?= $product_id ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-right"></i> رجوع
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-arrow-left"></i> التالي
                            </button>
                        </div>
                    </form>
                    
                    <!-- الخطوة 2: بيانات الاتصال -->
                    <form id="contact-form" method="POST" class="step-content <?= $current_step == 2 ? 'active' : '' ?>">
                        <input type="hidden" name="step" value="2">
                        
                        <h4 class="mb-3"><i class="bi bi-person-lines-fill"></i> بيانات الاتصال</h4>
                        
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">رقم الهاتف</label>
                            <input type="tel" class="form-control" id="phone_number" name="phone_number" 
                                placeholder="01XXXXXXXXX" required 
                                value="<?= isset($_SESSION['checkout_data']['phone_number']) ? htmlspecialchars($_SESSION['checkout_data']['phone_number']) : htmlspecialchars($user['phone'] ?? '') ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control" id="email" name="email" required 
                                value="<?= isset($_SESSION['checkout_data']['email']) ? htmlspecialchars($_SESSION['checkout_data']['email']) : htmlspecialchars($user['email'] ?? '') ?>">
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="checkout.php?product_id=<?= $product_id ?>&plan_id=<?= $plan_id ?>&step=1" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-right"></i> رجوع
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-arrow-left"></i> التالي
                            </button>
                        </div>
                    </form>
                    
                    <!-- الخطوة 3: إثبات الدفع -->
                    <form id="receipt-form" method="POST" enctype="multipart/form-data" class="step-content <?= $current_step == 3 ? 'active' : '' ?>">
                        <input type="hidden" name="step" value="3">
                        
                        <h4 class="mb-3"><i class="bi bi-receipt"></i> إثبات الدفع</h4>
                        
                        <div class="alert alert-info">
                            <h5><i class="bi bi-info-circle"></i> تعليمات الدفع:</h5>
                            <?php if (isset($_SESSION['checkout_data']['payment_method'])): ?>
                                <?php if ($_SESSION['checkout_data']['payment_method'] == 'vodafone_cash'): ?>
                                    <p>1. افتح تطبيق فودافون كاش على هاتفك</p>
                                    <p>2. أرسل المبلغ <strong><?= htmlspecialchars($plan['plan_price']) ?> جنيه</strong> إلى الرقم <strong>01012345678</strong></p>
                                    <p>3. احفظ صورة إثبات التحويل وارفعها هنا</p>
                                <?php else: ?>
                                    <p>1. افتح تطبيق إنستاباي على هاتفك</p>
                                    <p>2. أرسل المبلغ <strong><?= htmlspecialchars($plan['plan_price']) ?> جنيه</strong> إلى الرقم <strong>01012345678</strong></p>
                                    <p>3. احفظ صورة إثبات التحويل وارفعها هنا</p>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">صورة إثبات الدفع</label>
                            <div class="receipt-upload" onclick="document.getElementById('receipt_image').click()">
                                <img id="receipt-preview" src="#" alt="Preview" class="img-fluid">
                                <i class="bi bi-cloud-arrow-up" style="font-size: 2rem;"></i>
                                <p class="mt-2">اضغط لرفع صورة الإيصال</p>
                                <small class="text-muted">(JPG, PNG, GIF - الحد الأقصى 2MB)</small>
                            </div>
                            <input type="file" id="receipt_image" name="receipt_image" accept="image/*" class="d-none" required>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="checkout.php?product_id=<?= $product_id ?>&plan_id=<?= $plan_id ?>&step=2" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-right"></i> رجوع
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-arrow-left"></i> التالي
                            </button>
                        </div>
                    </form>
                    
                    <!-- الخطوة 4: تأكيد الطلب -->
                    <form id="confirm-form" method="POST" class="step-content <?= $current_step == 4 ? 'active' : '' ?>">
                        <input type="hidden" name="step" value="4">
                        
                        <h4 class="mb-3"><i class="bi bi-check-circle"></i> تأكيد الطلب</h4>
                        
                        <div class="card mb-4">
                            <div class="card-header bg-success text-white">
                                <h5><i class="bi bi-check2-circle"></i> تأكيد معلومات الطلب</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>طريقة الدفع:</strong> 
                                            <?php if (isset($_SESSION['checkout_data']['payment_method'])): ?>
                                                <?= $_SESSION['checkout_data']['payment_method'] == 'vodafone_cash' ? 'فودافون كاش' : 'إنستاباي' ?>
                                            <?php endif; ?>
                                        </p>
                                        <p><strong>رقم الهاتف:</strong> <?= isset($_SESSION['checkout_data']['phone_number']) ? htmlspecialchars($_SESSION['checkout_data']['phone_number']) : '' ?></p>
                                        <p><strong>البريد الإلكتروني:</strong> <?= isset($_SESSION['checkout_data']['email']) ? htmlspecialchars($_SESSION['checkout_data']['email']) : '' ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <?php if (isset($_SESSION['checkout_data']['receipt_image'])): ?>
                                            <img src="../uploads/receipts/<?= htmlspecialchars($_SESSION['checkout_data']['receipt_image']) ?>" class="img-fluid rounded" style="max-height: 150px;">
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="agree_terms" name="agree_terms" required>
                            <label class="form-check-label" for="agree_terms">
                                أوافق على <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">الشروط والأحكام</a>
                            </label>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="checkout.php?product_id=<?= $product_id ?>&plan_id=<?= $plan_id ?>&step=3" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-right"></i> رجوع
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> تأكيد الطلب
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal للشروط والأحكام -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">الشروط والأحكام</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>شروط الاستخدام:</h5>
                    <p>1. جميع المدفوعات غير قابلة للاسترداد بعد تأكيد الطلب.</p>
                    <p>2. يتحمل العميل مسؤولية التأكد من صحة المعلومات المقدمة.</p>
                    <p>3. يجب أن تكون صورة الإيصال واضحة وتظهر كافة التفاصيل.</p>
                    <p>4. قد تستغرق معالجة الطلب حتى 24 ساعة عمل.</p>
                    <p>5. يحق للشركة رفض أي طلب دون إبداء الأسباب.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">موافق</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // اختيار طريقة الدفع
        function selectPaymentMethod(method) {
            document.querySelectorAll('.payment-method').forEach(el => {
                el.classList.remove('selected');
            });
            
            document.getElementById(method).parentElement.classList.add('selected');
            document.getElementById(method).checked = true;
        }
        
        // عرض معاينة صورة الإيصال
        document.getElementById('receipt_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('receipt-preview');
                    preview.src = event.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
        
        // التحقق من صحة رقم الهاتف
        document.getElementById('phone_number').addEventListener('input', function() {
            if (!/^01[0-9]{9}$/.test(this.value)) {
                this.setCustomValidity('يجب أن يبدأ رقم الهاتف بـ 01 ويتكون من 11 رقمًا');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
    <script>
        // تهيئة EmailJS بالمفتاح العام
        (function() {
            emailjs.init('OL-bmAupqH0hzZS0X');
        })();
    </script>
</body>
</html>