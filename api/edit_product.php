<?php
require_once '../includes/config.php';

// جلب بيانات المنتج من قاعدة البيانات
$productId = $_GET['id'] ?? null;
if (!$productId) {
    header('Location: products.php');
    exit();
}

try {
    // جلب بيانات المنتج الأساسية
    $stmt = $pdo->prepare("
        SELECT 
            d.*
        FROM digital_products d
        WHERE d.id = ?
    ");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$product) {
        header('Location: products.php');
        exit();
    }
    
    // تحويل المميزات من JSON إلى مصفوفة
    $product['features'] = json_decode($product['features'], true) ?: [];
    
    // جلب خطط المنتج
    $stmt = $pdo->prepare("SELECT * FROM product_plans WHERE product_id = ? ORDER BY id");
    $stmt->execute([$productId]);
    $plans = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // تحويل مميزات كل خطة من JSON إلى مصفوفة
    foreach ($plans as &$plan) {
        $plan['plan_features'] = json_decode($plan['plan_features'], true) ?: [];
    }
    unset($plan);

} catch (PDOException $e) {
    die("خطأ في استرجاع بيانات المنتج: " . $e->getMessage());
}
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap"rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/dark-mode-index.css">
    
    <title>تعديل المنتج - <?= htmlspecialchars($product['product_name']) ?></title>
    <style>
        body {
            font-family: "Tajawal", sans-serif;
        }

        .feature-item {
            margin-bottom: 10px;
        }
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            display: none;
        }
        .plan-icon {
            font-size: 2rem;
            text-align: center;
            margin: 15px 0;
        }
        .basic-plan { color: #4CAF50; }
        .popular-plan { color: #FF9800; }
        .pro-plan { color: #9C27B0; }
        .other-plan { color: #607D8B; }
         /* إضافة أنماط جديدة للخطط */
        .plan-card {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f8f9fa;
        }
        .plan-header {
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .plan-title {
            font-weight: bold;
            font-size: 1.2rem;
        }
        .feature-item {
            margin-bottom: 10px;
        }
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            display: none;
        }
        .plan-icon {
            font-size: 2rem;
            text-align: center;
            margin: 15px 0;
        }
        .basic-plan { color: #4CAF50; }
        .popular-plan { color: #FF9800; }
        .pro-plan { color: #9C27B0; }
        .other-plan { color: #607D8B; }
        .add-plan-btn {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title">تعديل المنتج: <?= htmlspecialchars($product['product_name']) ?></h3>
                    </div>
                    <div class="card-body">
                        <form id="editProductForm" enctype="multipart/form-data">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            
                            <!-- معلومات المنتج الأساسية -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_name" class="form-label">اسم المنتج</label>
                                        <input type="text" class="form-control" id="product_name" name="product_name" 
                                            value="<?= htmlspecialchars($product['product_name']) ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="service_type" class="form-label">نوع الخدمة</label>
                                        <input type="text" class="form-control" id="service_type" name="service_type" 
                                            value="<?= htmlspecialchars($product['service_type'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_image" class="form-label">صورة المنتج</label>
                                        <input type="file" class="form-control" id="product_image" name="product_image" accept="image/*">
                                        <?php if ($product['image_path']): ?>
                                        <div class="mt-2">
                                            <img src="<?= $product['image_path'] ?>" alt="صورة المنتج" class="img-thumbnail" style="max-width: 200px;">
                                            <input type="hidden" name="current_image" value="<?= $product['image_path'] ?>">
                                        </div>
                                        <?php endif; ?>
                                        <img id="imagePreview" class="image-preview img-thumbnail" src="#" alt="Preview">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price" class="form-label">السعر الأساسي</label>
                                        <input type="number" step="0.01" class="form-control" id="price" name="price" 
                                            value="<?= $product['price'] ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="warranty_duration_days" class="form-label">مدة الضمان بالأيام (اختياري)</label>
                                        <input type="number" class="form-control" id="warranty_duration_days" name="warranty_duration_days" 
                                            value="<?= $product['warranty_duration_days'] ?? '' ?>" min="0">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="discount" class="form-label">الخصم (اختياري)</label>
                                        <input type="number" step="0.01" class="form-control" id="discount" name="discount" 
                                            value="<?= $product['discount'] ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="description" class="form-label">وصف المنتج</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required><?= htmlspecialchars($product['description']) ?></textarea>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="instructions" class="form-label">إرشادات الاستخدام</label>
                                <textarea class="form-control" id="instructions" name="instructions" rows="3"><?= htmlspecialchars($product['instructions'] ?? '') ?></textarea>
                            </div>
                            
                            <!-- مميزات المنتج -->
                            <div class="form-group mb-3" style="direction: ltr;">
                                <label class="form-label">مميزات المنتج</label>
                                <div id="featuresContainer">
                                    <?php foreach ($product['features'] as $index => $feature): ?>
                                    <div class="feature-item input-group mb-2">
                                        <input type="text" class="form-control" name="features[]" value="<?= htmlspecialchars($feature) ?>">
                                        <button type="button" class="btn btn-danger remove-feature"><i class="bi bi-trash"></i></button>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <button type="button" id="addFeature" class="btn btn-sm btn-primary mt-2">
                                    <i class="bi bi-plus"></i> إضافة ميزة
                                </button>
                            </div>
                            
                            <!-- قسم خطط المنتج -->
                            <div class="mb-4">
                                <h4 class="mb-3">خطط المنتج</h4>
                                
                                <button type="button" id="addPlan" class="btn btn-success add-plan-btn">
                                    <i class="bi bi-plus-circle"></i> إضافة خطة جديدة
                                </button>
                                
                                <div id="plansContainer">
                                    <?php foreach ($plans as $index => $plan): ?>
                                    <div class="plan-card mb-4" data-plan-id="<?= $plan['id'] ?>">
                                        <input type="hidden" name="plan_ids[]" value="<?= $plan['id'] ?>">
                                        
                                        <div class="plan-header d-flex justify-content-between align-items-center">
                                            <div class="plan-title">الخطة #<?= $index + 1 ?></div>
                                            <button type="button" class="btn btn-sm btn-danger remove-plan">
                                                <i class="bi bi-trash"></i> حذف الخطة
                                            </button>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">نوع الخطة</label>
                                                    <select class="form-select" name="plan_types[]" required>
                                                        <option value="1" <?= $plan['plan_type'] === '1' ? 'selected' : '' ?>>أساسي</option>
                                                        <option value="2" <?= $plan['plan_type'] === '2' ? 'selected' : '' ?>>شائع</option>
                                                        <option value="3" <?= $plan['plan_type'] === '3' ? 'selected' : '' ?>>احترافي</option>
                                                        <option value="custom" <?= $plan['plan_type'] === 'custom' ? 'selected' : '' ?>>مخصص</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">اسم الخطة</label>
                                                    <input type="text" class="form-control" name="plan_names[]" 
                                                        value="<?= htmlspecialchars($plan['plan_name']) ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">سعر الخطة</label>
                                                    <input type="number" step="0.01" class="form-control" name="plan_prices[]" 
                                                        value="<?= $plan['plan_price'] ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group mb-3" style="direction: ltr;">
                                            <label class="form-label">مميزات الخطة</label>
                                            <div class="plan-features-container">
                                                <?php foreach ($plan['plan_features'] as $feature): ?>
                                                <div class="feature-item input-group mb-2">
                                                    <input type="text" class="form-control" name="plan_features[<?= $plan['id'] ?>][]" 
                                                        value="<?= htmlspecialchars($feature) ?>">
                                                    <button type="button" class="btn btn-danger remove-plan-feature"><i class="bi bi-trash"></i></button>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-primary mt-2 add-plan-feature" data-plan-id="<?= $plan['id'] ?>">
                                                <i class="bi bi-plus"></i> إضافة ميزة
                                            </button>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <!-- خيارات المنتج -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_published" name="is_published" 
                                            <?= $product['is_published'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_published">منشور</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                                            <?= $product['is_featured'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_featured">منتج مميز</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_special_offer" name="is_special_offer" 
                                            <?= $product['is_special_offer'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_special_offer">عرض خاص</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="../admin/products.php" class="btn btn-secondary">رجوع</a>
                                <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script>
    $(document).ready(function() {
        // معاينة الصورة عند التغيير
        $('#product_image').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').attr('src', e.target.result).show();
                }
                reader.readAsDataURL(file);
            }
        });
        
        // إضافة ميزة منتج
        $('#addFeature').click(function() {
            const newFeature = `
                <div class="feature-item input-group mb-2">
                    <input type="text" class="form-control" name="features[]" placeholder="أدخل ميزة جديدة">
                    <button type="button" class="btn btn-danger remove-feature"><i class="bi bi-trash"></i></button>
                </div>
            `;
            $('#featuresContainer').append(newFeature);
        });
        
        // إضافة خطة جديدة
        $('#addPlan').click(function() {
            const planId = 'new_' + Date.now();
            const newPlan = `
                <div class="plan-card mb-4" data-plan-id="${planId}">
                    <input type="hidden" name="plan_ids[]" value="${planId}">
                    
                    <div class="plan-header d-flex justify-content-between align-items-center">
                        <div class="plan-title">خطة جديدة</div>
                        <button type="button" class="btn btn-sm btn-danger remove-plan">
                            <i class="bi bi-trash"></i> حذف الخطة
                        </button>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">نوع الخطة</label>
                                <select class="form-select" name="plan_types[]" required>
                                    <option value="1">أساسي</option>
                                    <option value="2">شائع</option>
                                    <option value="3">احترافي</option>
                                    <option value="custom" selected>مخصص</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">اسم الخطة</label>
                                <input type="text" class="form-control" name="plan_names[]" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">سعر الخطة</label>
                                <input type="number" step="0.01" class="form-control" name="plan_prices[]" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3" style="direction: ltr;">
                        <label class="form-label">مميزات الخطة</label>
                        <div class="plan-features-container">
                            <div class="feature-item input-group mb-2">
                                <input type="text" class="form-control" name="plan_features[${planId}][]">
                                <button type="button" class="btn btn-danger remove-plan-feature"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary mt-2 add-plan-feature" data-plan-id="${planId}">
                            <i class="bi bi-plus"></i> إضافة ميزة
                        </button>
                    </div>
                </div>
            `;
            $('#plansContainer').append(newPlan);
        });
        
        // إضافة ميزة لخطة محددة
        $(document).on('click', '.add-plan-feature', function() {
            const planId = $(this).data('plan-id');
            const newFeature = `
                <div class="feature-item input-group mb-2">
                    <input type="text" class="form-control" name="plan_features[${planId}][]">
                    <button type="button" class="btn btn-danger remove-plan-feature"><i class="bi bi-trash"></i></button>
                </div>
            `;
            $(this).siblings('.plan-features-container').append(newFeature);
        });
        
        // إزالة ميزة (استخدام event delegation)
        $(document).on('click', '.remove-feature, .remove-plan-feature', function() {
            $(this).closest('.feature-item').remove();
        });
        
        // إزالة خطة
        $(document).on('click', '.remove-plan', function() {
            if (confirm('هل أنت متأكد من حذف هذه الخطة؟')) {
                $(this).closest('.plan-card').remove();
            }
        });
        
        // إرسال النموذج
        $('#editProductForm').submit(function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // إضافة المميزات الفارغة للتأكد من إرسالها جميعًا
            $('input[name="features[]"]').each(function() {
                if ($(this).val().trim() === '') {
                    $(this).remove();
                }
            });
            
            $('input[name^="plan_features["]').each(function() {
                if ($(this).val().trim() === '') {
                    $(this).remove();
                }
            });
            
            $.ajax({
                url: './update_product.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(result) {
                    if (result.success) {
                        alert(result.message);
                        window.location.href = '../admin/products.php';
                    } else {
                        alert('حدث خطأ: ' + result.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    console.log("Full Response:", xhr.responseText);
                    alert('حدث خطأ أثناء الاتصال بالخادم. الرجاء التحقق من الكونسول للمزيد من التفاصيل.');
                }
            });
        });
    });
    </script>
</body>
</html>