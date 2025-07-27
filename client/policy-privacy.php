<?php

require_once '../includes/check_maintenance.php';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سياسة الخصوصية</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #1976D2;
            --secondary-blue: #42A5F5;
            --success-color: #4CAF50;
            --info-color: #2196F3;
            --warning-color: #FF9800;
            --danger-color: #F44336;
            --dark-color: #23234a;
            --light-color: #f8f9fa;
            --border-color: #e9ecef;
        }

        body {
            font-family: "Tajawal", sans-serif;
            background-color: white;
            color: #333;
            line-height: 1.8;
        }

        .gradient-header {
            border-bottom: 1px solid #e0e0e0;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: white;
            padding: 5rem 0;
            position: relative;
            overflow: hidden;
        }

        .gradient-header::before {
            content: "";
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            transform: rotate(30deg);
        }

        .section-title {
            text-align: center;
            color: var(--primary-blue);
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 3rem;
            font-weight: 700;
        }

        .section-title::after {
            content: "";
            position: absolute;
            bottom: 0;
            right: 50%;
            transform: translateX(50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-blue), var(--secondary-blue));
            border-radius: 3px;
        }

        .card {
            border: none;
            border-radius: 15px;
            border: 1px solid #e0e0e0 !important;
            height: 100%;
            background-color: white;
            overflow: hidden;
            position: relative;
            z-index: 1;
        }

        .policy-item {
            margin-bottom: 2rem;
            border: 1px solid #e0e0e0 !important;
            padding: 1.5rem;
            border-radius: 12px;
            background-color: white;
            position: relative;
            overflow: hidden;
        }

        .policy-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            color: white;
        }

        .policy-icon.privacy {
            background: linear-gradient(45deg, #9C27B0, #E91E63);
        }

        .policy-icon.data {
            background: linear-gradient(45deg, #3F51B5, #2196F3);
        }

        .policy-icon.use {
            background: linear-gradient(45deg, #FF5722, #FF9800);
        }

        .policy-icon.protection {
            background: linear-gradient(45deg, #009688, #4CAF50);
        }

        .policy-icon.rights {
            background: linear-gradient(45deg, #673AB7, #9C27B0);
        }

        .policy-icon.contact {
            background: linear-gradient(45deg, #00BCD4, #009688);
        }

        .feature-list p {
            position: relative;
            padding-right: 25px;
            margin-bottom: 0.5rem;
        }

        .feature-list p::before {
            content: "\f00c";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            color: var(--success-color);
        }

        .data-type-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 15px;
            font-size: 1.5rem;
            color: white;
        }

        .data-type-icon.personal {
            background: linear-gradient(45deg, #2196F3, #1976D2);
        }

        .data-type-icon.device {
            background: linear-gradient(45deg, #FF9800, #FB8C00);
        }

        .data-type-icon.payment {
            background: linear-gradient(45deg, #4CAF50, #43A047);
        }

        .data-type-icon.communication {
            background: linear-gradient(45deg, #E91E63, #C2185B);
        }

        .rights-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 15px;
            font-size: 1.5rem;
            color: white;
        }

        .rights-icon.access {
            background: linear-gradient(45deg, #2196F3, #1976D2);
        }

        .rights-icon.correction {
            background: linear-gradient(45deg, #FF9800, #FB8C00);
        }

        .rights-icon.delete {
            background: linear-gradient(45deg, #F44336, #E53935);
        }

        .rights-icon.objection {
            background: linear-gradient(45deg, #9C27B0, #8E24AA);
        }

        /* أزرار جديدة */
        .btn-primary {
            background: linear-gradient(90deg, #1976D2, #42A5F5);
            border: none;
            color: white;
            padding: 10px 20px;
        }

        .btn-outline-primary {
            border: 1px solid #1976D2;
            color: #1976D2;
            background: transparent;
            padding: 10px 20px;
        }

        .btn-outline-primary:hover {
            background: linear-gradient(90deg, #1976D2, #42A5F5);
            color: white;
        }

        /* إزالة الحركات والظلال */
        .card, .policy-item {
            transition: none !important;
            transform: none !important;
        }

        .card, .policy-item, .gradient-header {
            box-shadow: none !important;
        }

        .card:hover, .policy-item:hover {
            transform: none !important;
            box-shadow: none !important;
        }

        @media (max-width: 768px) {
            .gradient-header {
                padding: 3rem 0;
            }
            
            .section-title {
                font-size: 1.5rem;
                margin-bottom: 2rem;
            }
            
            .policy-icon {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <header class="gradient-header text-center">
        <div class="container">
            <h1 class="display-3 fw-bold mb-4"><i class="fas fa-shield-alt me-2"></i>سياسة الخصوصية</h1>
            <p class="lead fs-4">نحن نحمي بياناتك الشخصية بكل جدية</p>
        </div>
    </header>

    <main class="container my-5 py-4">
        <section class="mb-5 py-4">
            <div class="card p-4">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center mb-3 mb-md-0">
                        <div class="policy-icon privacy mx-auto">
                            <i class="fas fa-user-lock"></i>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <p class="mb-0 fs-5">نحن في A.S UNLOCK نلتزم بحماية خصوصية بياناتك الشخصية. هذه السياسة توضح كيفية جمع واستخدام وحماية معلوماتك وفقًا لأعلى المعايير الأمنية.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-5 py-4">
            <h2 class="section-title">المعلومات التي نجمعها</h2>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="policy-item">
                        <div class="d-flex align-items-center mb-3">
                            <div class="data-type-icon personal">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <h3 class="h4 mb-0">البيانات الشخصية</h3>
                        </div>
                        <p>الاسم وبيانات الاتصال الأساسية</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="policy-item">
                        <div class="d-flex align-items-center mb-3">
                            <div class="data-type-icon device">
                                <i class="fas fa-tablet-alt"></i>
                            </div>
                            <h3 class="h4 mb-0">معلومات الجهاز</h3>
                        </div>
                        <p>نوع التابلت ومواصفاته الفنية</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="policy-item">
                        <div class="d-flex align-items-center mb-3">
                            <div class="data-type-icon payment">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <h3 class="h4 mb-0">بيانات الدفع</h3>
                        </div>
                        <p>تفاصيل التحويلات المالية</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="policy-item">
                        <div class="d-flex align-items-center mb-3">
                            <div class="data-type-icon communication">
                                <i class="fas fa-headset"></i>
                            </div>
                            <h3 class="h4 mb-0">سجلات التواصل</h3>
                        </div>
                        <p>محادثات الدعم الفني والاستفسارات</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-5 py-4">
            <h2 class="section-title">كيفية استخدام المعلومات</h2>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="policy-item">
                        <div class="policy-icon use mx-auto mb-4">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <div class="feature-list text-center">
                            <p>نستخدم معلوماتك حصريًا لتقديم خدماتنا بشكل فعال، مع تحسين تجربة المستخدم بشكل مستمر بناءً على احتياجاتك وملاحظاتك.</p>
                            <p>جميع عمليات معالجة البيانات تتم وفقًا للقوانين واللوائح المعمول بها، مع ضمان أعلى درجات الشفافية.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-5 py-4">
            <h2 class="section-title">حماية البيانات</h2>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="policy-item">
                        <div class="policy-icon protection mx-auto mb-4">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="text-center">
                            <h3 class="h4 mb-3">تشفير متقدم</h3>
                            <p>نطبق أحدث تقنيات التشفير لحماية بياناتك، مع استخدام بروتوكولات أمنية صارمة لضمان عدم وصول أي معلومات لأطراف ثالثة غير مصرح لها. جميع البيانات تخضع لمعايير الحماية العالمية ونقوم بتحديث أنظمتنا الأمنية بشكل دوري.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-5 py-4">
            <h2 class="section-title">حقوقك</h2>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="policy-item">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rights-icon access">
                                <i class="fas fa-eye"></i>
                            </div>
                            <h3 class="h4 mb-0">الحق في الوصول</h3>
                        </div>
                        <p>يمكنك طلب نسخة من بياناتك الشخصية لدينا</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="policy-item">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rights-icon correction">
                                <i class="fas fa-edit"></i>
                            </div>
                            <h3 class="h4 mb-0">الحق في التصحيح</h3>
                        </div>
                        <p>يمكنك طلب تصحيح أي بيانات غير دقيقة</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="policy-item">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rights-icon delete">
                                <i class="fas fa-trash-alt"></i>
                            </div>
                            <h3 class="h4 mb-0">الحق في الحذف</h3>
                        </div>
                        <p>يمكنك طلب حذف بياناتك في حالات معينة</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="policy-item">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rights-icon objection">
                                <i class="fas fa-ban"></i>
                            </div>
                            <h3 class="h4 mb-0">الحق في الاعتراض</h3>
                        </div>
                        <p>يمكنك الاعتراض على معالجة بياناتك</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-4">
            <h2 class="section-title">التواصل معنا</h2>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="policy-item">
                        <div class="policy-icon contact mx-auto mb-4">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="text-center">
                            <p class="mb-4">إذا كان لديك أي استفسارات حول سياسة الخصوصية أو تريد ممارسة أي من حقوقك، يرجى التواصل مع فريق حماية البيانات الخاص بنا عبر المصادر التالية:</p>
                            <div class="d-flex justify-content-center">
                                <a href="#" class="btn btn-primary me-3"><i class="fab fa-whatsapp me-1"></i> واتساب</a>
                                <a href="#" class="btn btn-outline-primary"><i class="fas fa-phone me-1"></i> هاتف</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>