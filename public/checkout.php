<?php
session_start();
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit;
}

$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
$plan_id = isset($_GET['plan_id']) ? (int)$_GET['plan_id'] : 0;
$current_step = isset($_GET['step']) ? (int)$_GET['step'] : 1;

$user_id = $_SESSION['user_id'];
$user_stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$user_stmt->execute([$user_id]);
$user = $user_stmt->fetch(PDO::FETCH_ASSOC);

$product = [];
$product_stmt = $pdo->prepare("SELECT * FROM digital_products WHERE id = ?");
$product_stmt->execute([$product_id]);
$product = $product_stmt->fetch(PDO::FETCH_ASSOC);

$plan = [];
$plan_stmt = $pdo->prepare("SELECT * FROM product_plans WHERE id = ? AND product_id = ?");
$plan_stmt->execute([$plan_id, $product_id]);
$plan = $plan_stmt->fetch(PDO::FETCH_ASSOC);

if (empty($product) || empty($plan)) {
    header("Location: ../error/404.php");
    exit;
}

$plan_features = json_decode($plan['plan_features'], true);

if (!isset($_SESSION['checkout_data'])) {
    $_SESSION['checkout_data'] = [];
}

if ($current_step > 1 && empty($_SESSION['checkout_data'])) {
    $_SESSION['checkout_error'] = 'يجب إكمال بيانات الدفع أولاً';
    header("Location: checkout.php?product_id=$product_id&plan_id=$plan_id&step=1");
    exit;
}

if ($current_step > 2 && !isset($_SESSION['checkout_data']['payment_method'])) {
    $_SESSION['checkout_error'] = 'يجب اختيار طريقة الدفع أولاً';
    header("Location: checkout.php?product_id=$product_id&plan_id=$plan_id&step=1");
    exit;
}

if ($current_step > 3 && (!isset($_SESSION['checkout_data']['phone_number']) || !isset($_SESSION['checkout_data']['email']))) {
    $_SESSION['checkout_error'] = 'يجب إكمال بيانات الاتصال أولاً';
    header("Location: checkout.php?product_id=$product_id&plan_id=$plan_id&step=2");
    exit;
}

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
            $subscription_email = $_POST['subscription_email'] ?? '';
            
            if (empty($phone_number) || !preg_match('/^01[0-9]{9}$/', $phone_number)) {
                $errors[] = 'رقم الهاتف غير صالح';
            }
            
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'البريد الإلكتروني غير صالح';
            }
            
            if ($product['is_special_offer'] == 1 && !empty($subscription_email) && !filter_var($subscription_email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'بريد الاشتراك غير صالح';
            }
            
            if (empty($errors)) {
                // حفظ رقم الهاتف في جدول users
                try {
                    $update_stmt = $pdo->prepare("UPDATE users SET phone = ? WHERE id = ?");
                    $update_stmt->execute([$phone_number, $user_id]);
                    
                    $_SESSION['checkout_data']['phone_number'] = $phone_number;
                    $_SESSION['checkout_data']['email'] = $email;
                    
                    // حفظ بريد الاشتراك إذا كان موجوداً
                    if ($product['is_special_offer'] == 1 && !empty($subscription_email)) {
                        $_SESSION['checkout_data']['subscription_email'] = $subscription_email;
                    }
                    
                    $current_step = 3;
                    header("Location: checkout.php?product_id=$product_id&plan_id=$plan_id&step=3");
                    exit;
                } catch (PDOException $e) {
                    $errors[] = 'حدث خطأ أثناء تحديث رقم الهاتف: ' . $e->getMessage();
                }
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
                    (user_id, product_id, plan_id, payment_method, phone_number, email, subscription_email, receipt_image, amount) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $user_id,
                    $product_id,
                    $plan_id,
                    $_SESSION['checkout_data']['payment_method'],
                    $_SESSION['checkout_data']['phone_number'],
                    $_SESSION['checkout_data']['email'],
                    $_SESSION['checkout_data']['subscription_email'] ?? null,
                    $_SESSION['checkout_data']['receipt_image'],
                    $plan['plan_price']
                ]);
                
                // إرسال البريد الإلكتروني عبر EmailJS
                $order_id = $pdo->lastInsertId();
                $order_data = [
                    'order_id' => $order_id,
                    'order_link' => 'https://a.s-unlock.ct.ws/admin/order.php?id=' . $order_id,
                    'product_name' => $product['product_name'],
                    'plan_name' => $plan['plan_name'],
                    'plan_price' => $plan['plan_price'],
                    'payment_method' => $_SESSION['checkout_data']['payment_method'] == 'vodafone_cash' ? 'فودافون كاش' : 'إنستاباي',
                    'phone_number' => $_SESSION['checkout_data']['phone_number'],
                    'email' => $_SESSION['checkout_data']['email'],
                    'subscription_email' => $_SESSION['checkout_data']['subscription_email'] ?? '',
                    'receipt_image' => '../uploads/receipts/' . $_SESSION['checkout_data']['receipt_image'],
                    'order_date' => date('Y-m-d H:i:s')
                ];
                
                unset($_SESSION['checkout_data']);
                $_SESSION['order_success'] = true;
                $_SESSION['emailjs_data'] = $order_data;
                
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

if ($user && $user['banned']) {
    session_unset();
    session_destroy();
    $_SESSION['login_error'] = 'تم حظر حسابك. الرجاء التواصل مع الإدارة.';
    header('Location: login.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="A.S UNLOCK" />
    <meta name="description" content="A.S UNLOCK - خبراء فتح وإصلاح أجهزة التابلت بأحدث التقنيات. خدمات سريعة ومضمونة مع دعم على مدار الساعة." />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://as_unlock.ct.ws" />
    <meta property="og:title" content="A.S UNLOCK" />
    <meta property="og:description" content="A.S UNLOCK - خبراء فتح وإصلاح أجهزة التابلت بأحدث التقنيات. خدمات سريعة ومضمونة مع دعم على مدار الساعة." />
    <meta property="og:image" content="https://as_unlock.ct.ws/" />
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="https://as_unlock.ct.ws/assets/image/favicon.ico" />
    <meta property="twitter:title" content="A.S UNLOCK" />
    <meta property="twitter:description" content="A.S UNLOCK - خبراء فتح وإصلاح أجهزة التابلت بأحدث التقنيات. خدمات سريعة ومضمونة مع دعم على مدار الساعة." />
    <meta property="twitter:image" content="https://as_unlock.ct.ws/assets/image/favicon.ico" />
    <link rel="icon" type="image/x-icon" href="../assets/image/favicon.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/checkout.css">
    <link rel="stylesheet" href="../assets/css/dark-mode-index.css">
    <title>إتمام الطلب - <?= htmlspecialchars($product['product_name']) ?> - <?= htmlspecialchars($plan['plan_name']) ?></title>
</head>
<body>
    <li style="display: none;" class="dark-mode-toggle"><a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-circle-half" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 0 8 1zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16" /></svg></a></li>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="checkout-container p-4 p-md-5">
                    <h2 class="text-center mb-4"><i class="bi bi-cart-check"></i> إتمام الطلب</h2>
                    
                    <?php if (isset($_SESSION['checkout_error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show mb-4">
                            <?= htmlspecialchars($_SESSION['checkout_error']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['checkout_error']); ?>
                    <?php endif; ?>

                    <!-- شريط تقدم الخطوات -->
                    <div class="step-progress mb-5">
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
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <!-- ملخص الطلب -->
                    <div class="card border-0 shadow-sm mb-5">
                        <div class="card-header bg-transparent border-0 text-white py-3">
                            <h4 class="mb-0"><i class="bi bi-receipt"></i> ملخص الطلب</h4>
                        </div>
                        <div class="card-body py-4">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <p class="mb-2"><strong><i class="bi bi-box-seam"></i> المنتج:</strong> <?= htmlspecialchars($product['product_name']) ?></p>
                                    <p class="mb-0"><strong><i class="bi bi-stack"></i> الخطة:</strong> <?= htmlspecialchars($plan['plan_name']) ?></p>
                                </div>
                                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                    <p class="total-price mb-0"><i class="bi bi-currency-pound"></i> المجموع: <?= htmlspecialchars($plan['plan_price']) ?> جنيه</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- الخطوة 1: اختيار طريقة الدفع -->
                    <form id="payment-form" method="POST" enctype="multipart/form-data" class="step-content <?= $current_step == 1 ? 'active' : '' ?>">
                        <input type="hidden" name="step" value="1">
                        
                        <h4 class="mb-4"><i class="bi bi-credit-card"></i> طريقة الدفع</h4>
                        
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="payment-method <?= isset($_SESSION['checkout_data']['payment_method']) && $_SESSION['checkout_data']['payment_method'] == 'vodafone_cash' ? 'selected' : '' ?>" onclick="selectPaymentMethod('vodafone_cash')">
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
                                <div class="payment-method <?= isset($_SESSION['checkout_data']['payment_method']) && $_SESSION['checkout_data']['payment_method'] == 'instapay' ? 'selected' : '' ?>" onclick="selectPaymentMethod('instapay')">
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
                        
                        <div class="d-flex justify-content-between pt-3">
                            <a href="products.php?id=<?= $product_id ?>" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-right"></i> رجوع
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-arrow-left"></i> التالي
                            </button>
                        </div>
                    </form>
                    
                    <!-- الخطوة 2: بيانات الاتصال -->
                    <form id="contact-form" method="POST" class="step-content <?= $current_step == 2 ? 'active' : '' ?>">
                        <input type="hidden" name="step" value="2">
                        
                        <h4 class="mb-4"><i class="bi bi-person-lines-fill"></i> بيانات الاتصال</h4>
                        
                        <div class="mb-4">
                            <label for="phone_number" class="form-label">رقم الهاتف</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                <input type="tel" class="form-control" id="phone_number" name="phone_number" 
                                    placeholder="01XXXXXXXXX" required 
                                    value="<?= isset($_SESSION['checkout_data']['phone_number']) ? htmlspecialchars($_SESSION['checkout_data']['phone_number']) : htmlspecialchars($user['phone'] ?? '') ?>">
                            </div>
                            <div class="form-text">يجب أن يبدأ بـ 01 ويتكون من 11 رقمًا</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" required 
                                    value="<?= isset($_SESSION['checkout_data']['email']) ? htmlspecialchars($_SESSION['checkout_data']['email']) : htmlspecialchars($user['email'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <?php if ($product['is_special_offer'] == 1): ?>
                            <div class="mb-4">
                                <label for="subscription_email" class="form-label">بريد الاشتراك</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope-at"></i></span>
                                    <input type="email" class="form-control" id="subscription_email" name="subscription_email" 
                                        value="<?= isset($_SESSION['checkout_data']['subscription_email']) ? htmlspecialchars($_SESSION['checkout_data']['subscription_email']) : '' ?>">
                                </div>
                                <div class="form-text">سيتم استخدام هذا البريد لإرسال بيانات الاشتراك</div>
                            </div>
                            
                            <?php if (!empty($product['instructions'])): ?>
                                <div class="card mb-4 border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <i class="bi bi-info-circle"></i> إرشادات مهمة
                                    </div>
                                    <div class="card-body">
                                        <?= nl2br(htmlspecialchars($product['instructions'])) ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <div class="d-flex justify-content-between pt-3">
                            <a href="checkout.php?product_id=<?= $product_id ?>&plan_id=<?= $plan_id ?>&step=1" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-right"></i> رجوع
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-arrow-left"></i> التالي
                            </button>
                        </div>
                    </form>
                    
                    <!-- الخطوة 3: إثبات الدفع -->
                    <form id="receipt-form" method="POST" enctype="multipart/form-data" class="step-content <?= $current_step == 3 ? 'active' : '' ?>">
                        <input type="hidden" name="step" value="3">
                        
                        <h4 class="mb-4"><i class="bi bi-receipt"></i> إثبات الدفع</h4>
                        
                        <div class="alert alert-info mb-4">
                            <h5 class="d-flex align-items-center"><i class="bi bi-info-circle me-2"></i> تعليمات الدفع:</h5>
                            <?php if (isset($_SESSION['checkout_data']['payment_method'])): ?>
                                <?php if ($_SESSION['checkout_data']['payment_method'] == 'vodafone_cash'): ?>
                                    <ol class="mb-0">
                                        <li>افتح تطبيق فودافون كاش على هاتفك</li>
                                        <li>أرسل المبلغ <strong><?= htmlspecialchars($plan['plan_price']) ?> جنيه</strong> إلى الرقم <strong> 01069062005</strong></li>
                                        <li>احفظ صورة إثبات التحويل وارفعها هنا</li>
                                    </ol>
                                <?php else: ?>
                                    <ol class="mb-0">
                                        <li>افتح تطبيق إنستاباي على هاتفك</li>
                                        <li>أرسل المبلغ <strong><?= htmlspecialchars($plan['plan_price']) ?> جنيه</strong> إلى الرقم <strong> 01069062005</strong></li>
                                        <li>احفظ صورة إثبات التحويل وارفعها هنا</li>
                                    </ol>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">صورة إثبات الدفع</label>
                            <div class="receipt-upload" onclick="document.getElementById('receipt_image').click()">
                                <img id="receipt-preview" src="#" alt="Preview">
                                <i class="bi bi-cloud-arrow-up" style="font-size: 2.5rem;"></i>
                                <p class="mt-2 fw-bold">اضغط لرفع صورة الإيصال</p>
                                <small class="text-muted">(JPG, PNG, GIF - الحد الأقصى 2MB)</small>
                            </div>
                            <input type="file" id="receipt_image" name="receipt_image" accept="image/*" class="d-none" required>
                        </div>
                        
                        <div class="d-flex justify-content-between pt-3">
                            <a href="checkout.php?product_id=<?= $product_id ?>&plan_id=<?= $plan_id ?>&step=2" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-right"></i> رجوع
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-arrow-left"></i> التالي
                            </button>
                        </div>
                    </form>
                    
                    <!-- الخطوة 4: تأكيد الطلب -->
                    <form id="confirm-form" method="POST" class="step-content <?= $current_step == 4 ? 'active' : '' ?>">
                        <input type="hidden" name="step" value="4">
                        
                        <h4 class="mb-4"><i class="bi bi-check-circle"></i> تأكيد الطلب</h4>
                        
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-transparent border-0 text-white py-3">
                                <h5 class="mb-0"><i class="bi bi-check2-circle"></i> تأكيد معلومات الطلب</h5>
                            </div>
                            <div class="card-body py-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-2"><strong><i class="bi bi-credit-card"></i> طريقة الدفع:</strong> 
                                            <?php if (isset($_SESSION['checkout_data']['payment_method'])): ?>
                                                <?= $_SESSION['checkout_data']['payment_method'] == 'vodafone_cash' ? 'فودافون كاش' : 'إنستاباي' ?>
                                            <?php endif; ?>
                                        </p>
                                        <p class="mb-2"><strong><i class="bi bi-phone"></i> رقم الهاتف:</strong> <?= isset($_SESSION['checkout_data']['phone_number']) ? htmlspecialchars($_SESSION['checkout_data']['phone_number']) : '' ?></p>
                                        <p class="mb-0"><strong><i class="bi bi-envelope"></i> البريد الإلكتروني:</strong> <?= isset($_SESSION['checkout_data']['email']) ? htmlspecialchars($_SESSION['checkout_data']['email']) : '' ?></p>
                                    </div>
                                    <div class="col-md-6 mt-3 mt-md-0">
                                        <?php if (isset($_SESSION['checkout_data']['receipt_image'])): ?>
                                            <div class="border p-2 rounded text-center">
                                                <img src="../uploads/receipts/<?= htmlspecialchars($_SESSION['checkout_data']['receipt_image']) ?>" class="img-fluid rounded" style="max-height: 150px;">
                                                <p class="mt-2 small text-muted">صورة الإيصال</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="agree_terms" name="agree_terms" required>
                            <label class="form-check-label" for="agree_terms">
                                أوافق على <a href="../client/policy-privacy.php" class="text-primary" data-bs-toggle="" data-bs-target="#termsModal">الشروط والأحكام</a>
                            </label>
                        </div>
                        
                        <div class="d-flex justify-content-between pt-3">
                            <a href="checkout.php?product_id=<?= $product_id ?>&plan_id=<?= $plan_id ?>&step=3" class="btn btn-outline-secondary px-4">
                                <i class="bi bi-arrow-right"></i> رجوع
                            </a>
                            <button type="submit" class="btn btn-success px-4">
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
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="termsModalLabel">الشروط والأحكام</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 class="mb-3">شروط الاستخدام:</h5>
                    <ol>
                        <li class="mb-2"><a href="../client/policy-privacy.html">الالتزام بالحماية: نحمي بياناتك الشخصية بأعلى المعايير الأمنية والقانونية.</a></li>
                    </ol>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">موافق</button>
                    <a class="btn" href="../client/policy-privacy.html">الذهاب</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/dark-mode.js"></script>
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
                    preview.nextElementSibling.style.display = 'none';
                    preview.nextElementSibling.nextElementSibling.style.display = 'none';
                    preview.nextElementSibling.nextElementSibling.nextElementSibling.style.display = 'none';
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


<?php
echo "سلام";
?>