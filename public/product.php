<?php

require_once '../includes/check_maintenance.php';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عروض خاصة</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../assets/image/favicon.ico">
    <link rel="stylesheet" href="../assets/css/dark-mode-index.css">
    <style>
        .modal-header .btn-close {margin: 0;}

        :root {
            --primary-color: #1976D2;
            --secondary-color: #1976D2;
            --basic-color: #64748b;
            --popular-color: #10b981;
            --premium-color: #f59e0b;
            --text-color: #334155;
            --light-text: #64748b;
            --border-color: #e2e8f0;
            --light-bg: #f8fafc;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Tajawal", sans-serif;
        }

        body {
            background-color: #f1f5f9;
            padding: 40px 20px;
            line-height: 1.6;
        }

        .container {
            max-width: 1300px;
            margin: 0 auto;
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 60px;
        }

        .header h1 {
            color: var(--primary-color);
            font-size: 2.5rem;
            margin-bottom: 15px;
            font-weight: 800;
            position: relative;
            display: inline-block;
        }

        .header h1:after {
            content: '';
            position: absolute;
            bottom: -10px;
            right: 50%;
            transform: translateX(50%);
            width: 80px;
            height: 4px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .header p {
            color: var(--light-text);
            font-size: 1.2rem;
            max-width: 700px;
            margin: 20px auto 0;
        }

        .cards-container {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: center;
            align-items: stretch;
        }

        .card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            flex: 1;
            min-width: 60px;
            max-width: 360px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
            position: relative;
            border: 1px solid var(--border-color);
        }

        .card:hover {
            transform: translateY(-10px);
        }

        .card-header {
            padding: 30px;
            text-align: center;
            color: white;
            position: relative;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
            height: 180px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .card-header.basic {
            background: linear-gradient(135deg, var(--basic-color), #94a3b8);
        }

        .card-header.popular {
            background: linear-gradient(135deg, var(--popular-color), #34d399);
        }

        .card-header.premium {
            background: linear-gradient(135deg, var(--premium-color), #fbbf24);
        }

        .card-header h2 {
            font-size: 1.6rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            font-weight: 700;
        }

        .price-container {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            gap: 5px;
            margin-top: 10px;
        }

        .price {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1;
        }

        .currency {
            font-size: 3rem;
            font-weight: 600;
            line-height: 1;
        }


        .card-body {
            padding: 30px;
            position: relative;
        }

        .features-list {
            list-style-type: none;
            margin-bottom: 30px;
        }

        .features-list li {
            padding: 12px 0;
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text-color);
            position: relative;
            padding-right: 10px;
        }

        .features-list li:not(:last-child):after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 100%;
            height: 1px;
            background: var(--border-color);
        }

        .feature-icon {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 8px;
            font-size: 0.9rem;
            color: var(--primary-color);
            flex-shrink: 0;
        }

        .card.basic .feature-icon {
            background: rgba(100, 116, 139, 0.1);
            color: var(--basic-color);
        }

        .card.popular .feature-icon {
            background: rgba(16, 185, 129, 0.1);
            color: var(--popular-color);
        }

        .card.premium .feature-icon {
            background: rgba(245, 158, 11, 0.1);
            color: var(--premium-color);
        }

        .card-footer {
            padding: 0 30px 30px;
            text-align: center;
        }

        .btn {
            display: inline-block;
            padding: 16px 30px;
            border-radius: 12px;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            width: 100%;
            font-size: 1.1rem;
            position: relative;
            overflow: hidden;
        }

        .btn:before {
            content: '';
            position: absolute;
            top: 0;
            right: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }

        .btn:hover:before {
            right: 100%;
        }

        .btn.basic {
            background: var(--basic-color);
        }

        .btn.popular {
            background: var(--popular-color);
        }

        .btn.premium {
            background: var(--premium-color);
        }

        .popular-tag {
            position: absolute;
            top: 20px;
            left: -5px;
            background: var(--popular-color);
            color: white;
            padding: 8px 25px;
            font-size: 0.9rem;
            font-weight: 700;
            transform: rotate(-45deg);
            transform-origin: right top;
            z-index: 1;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .card.popular {
            border: 2px solid var(--popular-color);
        }

        @media (max-width: 768px) {
            .cards-container {
                flex-direction: column;
                align-items: center;
            }
            
            .card {
                max-width: 100%;
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <li style="display: none;" class="dark-mode-toggle"><a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-circle-half" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 0 8 1zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16" /></svg></a></li>
    <div class="container">
        <div class="header">
            <h1>عروضنا المميزة</h1>
            <p>اختر الباقة التي تناسب احتياجاتك واستمتع بأفضل العروض الحصرية مع ضمان وجودة لا مثيل لها</p>
        </div>

        <div class="cards-container">
            <?php
            require_once '../includes/config.php';

            // جلب جميع الخطط مع ميزاتها
            $stmt = $pdo->query("
                SELECT p.*, GROUP_CONCAT(pf.feature SEPARATOR '|||') as features 
                FROM plans p 
                LEFT JOIN plan_features pf ON p.id = pf.plan_id 
                WHERE p.status = 1
                GROUP BY p.id 
                ORDER BY p.best_seller DESC, p.price ASC
            ");
            $plans = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // فصل المنتج المميز عن باقي المنتجات
            $bestSellerPlan = null;
            $otherPlans = [];

            foreach ($plans as $plan) {
                if ($plan['best_seller'] && !$bestSellerPlan) {
                    $bestSellerPlan = $plan;
                } else {
                    $otherPlans[] = $plan;
                }
            }

            // إدخال المنتج المميز في منتصف المصفوفة
            if ($bestSellerPlan) {
                $middleIndex = intval(count($otherPlans) / 2);
                array_splice($otherPlans, $middleIndex, 0, [$bestSellerPlan]);
            }

            $plans = $otherPlans;

            // ألوان لكل بطاقة (سيتم استخدام الأيقونة من DB بدلاً من الثوابت)
            $cardClasses = ['basic', 'popular', 'premium'];

            $index = 0;
            foreach ($plans as $plan) {
                $cardClass = $cardClasses[$index % count($cardClasses)];
                $features = explode('|||', $plan['features']);
            ?>
            <div class="card <?php echo $cardClass; ?>">
                <?php if ($plan['best_seller']): ?>
                <div class="popular-tag">الأكثر طلباً</div>
                <?php endif; ?>
                
                <div class="card-header <?php echo $cardClass; ?>">
                    <h2>
                        <?php if ($plan['icon']): ?>
                        <i class="<?php echo htmlspecialchars($plan['icon']); ?>"></i>
                        <?php else: ?>
                        <i class="fas fa-star"></i> <!-- أيقونة افتراضية إذا لم يتم تحديد أيقونة -->
                        <?php endif; ?>
                        <?php echo htmlspecialchars($plan['name']); ?>
                    </h2>
                    <div class="price-container">
                        <?php if ($plan['discount'] > 0): ?>
                        <div class="original-price">
                            <?php echo number_format($plan['price'] + ($plan['price'] * $plan['discount'] / 100), 2); ?> ج
                        </div>
                        <?php endif; ?>
                        <div class="price"><?php echo number_format($plan['price'], 2); ?></div>
                        <div class="currency">ج</div>
                    </div>
                </div>
                
                <div class="card-body">
                    <ul class="features-list">
                        <?php foreach ($features as $feature): ?>
                        <li><span class="feature-icon"><i class="fas fa-check"></i></span> <?php echo htmlspecialchars($feature); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="card-footer">
                    <a href="#" class="btn <?php echo $cardClass; ?> get-plan-btn" 
                    data-plan-id="<?php echo $plan['id']; ?>" 
                    data-plan-name="<?php echo htmlspecialchars($plan['name']); ?>"
                    data-plan-price="<?php echo $plan['price']; ?>">
                    احصل عليها الآن
                    </a>
                </div>
            </div>
            <?php 
                $index++;
            } 
            ?>
        </div>
    </div>
    <!-- نافذة الدفع -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إكمال عملية الشراء</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="loginAlert" class="alert alert-warning d-none">
                        يرجى <a href="login.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>">تسجيل الدخول</a> لإكمال عملية الشراء
                    </div>
                    
                    <div id="paymentForm" class="d-none">
                        <h6>الخطة: <span id="selectedPlanName"></span></h6>
                        <h6>السعر: <span id="selectedPlanPrice"></span> ج</h6>
                        <h6>حول المبلغ على هذا الرقم وصور الإصال 01069062005<span></span></h6>
                        
                        <form id="orderForm" enctype="multipart/form-data">
                            <input type="hidden" name="plan_id" id="planId">
                            
                            <div class="mb-3">
                                <label class="form-label">طريقة الدفع</label>
                                <select class="form-select" name="payment_method" required>
                                    <option value="">اختر طريقة الدفع</option>
                                    <option value="vodafone_cash">فودافون كاش 01069062005</option>
                                    <option value="instapay">انستا باي 01069062005</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">رقم الهاتف</label>
                                <input type="text" class="form-control" name="phone_number" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">البريد الإلكتروني</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">بريد الاشتراك (إن وجد)</label>
                                <input type="email" class="form-control" name="subscription_email">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">إيصال الدفع</label>
                                <input type="file" class="form-control" name="receipt_image" accept="image/*" required>
                                <small class="text-muted">يرجى رفع صورة واضحة للإيصال</small>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">تأكيد الطلب</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/adds_products.js"></script>
    <script src="../assets/js/dark-mode.js"></script>
</body>
</html>