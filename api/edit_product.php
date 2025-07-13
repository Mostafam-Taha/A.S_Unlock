<?php
require_once '../includes/config.php';

// جلب بيانات المنتج من قاعدة البيانات
$productId = $_GET['id'] ?? null;
if (!$productId) {
    header('Location: products.php');
    exit();
}

try {
    $stmt = $pdo->prepare("
        SELECT 
            d.*,
            p.plan_type, p.plan_name, p.plan_price, p.plan_features
        FROM digital_products d
        LEFT JOIN product_plans p ON d.id = p.product_id
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
    $product['plan_features'] = json_decode($product['plan_features'], true) ?: [];
    
} catch (PDOException $e) {
    die("خطأ في استرجاع بيانات المنتج: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل المنتج - <?= htmlspecialchars($product['product_name']) ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
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
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
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
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price" class="form-label">السعر</label>
                                        <input type="number" step="0.01" class="form-control" id="price" name="price" 
                                            value="<?= $product['price'] ?>" required>
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
                                <textarea class="form-control" id="instructions" name="instructions" rows="3"><?= htmlspecialchars($product['instructions']) ?></textarea>
                            </div>
                            
                            <!-- مميزات المنتج -->
                            <div class="form-group mb-3">
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
                            
                            <!-- قسم إكمال البيانات - الخطط -->
                            <div class="card mb-3">
                                <div class="card-header bg-secondary text-white">
                                    <h4 class="card-title">إكمال البيانات - الخطط</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label for="plan_type" class="form-label">نوع الخطة</label>
                                        <select class="form-select" id="plan_type" name="plan_type">
                                            <option value="1" <?= $product['plan_type'] == 1 ? 'selected' : '' ?>>الأساسية</option>
                                            <option value="2" <?= $product['plan_type'] == 2 ? 'selected' : '' ?>>الاكثر طلبًا</option>
                                            <option value="3" <?= $product['plan_type'] == 3 ? 'selected' : '' ?>>احترافية</option>
                                            <option value="4" <?= $product['plan_type'] == 4 ? 'selected' : '' ?>>أخرى</option>
                                        </select>
                                        <div class="plan-icon">
                                            <?php
                                            $iconClass = '';
                                            switch($product['plan_type']) {
                                                case 1: $iconClass = 'bi-star-fill basic-plan'; break;
                                                case 2: $iconClass = 'bi-gem popular-plan'; break;
                                                case 3: $iconClass = 'bi-award pro-plan'; break;
                                                default: $iconClass = 'bi-question-circle other-plan';
                                            }
                                            ?>
                                            <i class="bi <?= $iconClass ?>" id="planIcon"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="plan_name" class="form-label">اسم الخطة</label>
                                                <input type="text" class="form-control" id="plan_name" name="plan_name" 
                                                    value="<?= htmlspecialchars($product['plan_name']) ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="plan_price" class="form-label">سعر الخطة</label>
                                                <input type="number" step="0.01" class="form-control" id="plan_price" name="plan_price" 
                                                    value="<?= $product['plan_price'] ?>">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">مميزات الخطة</label>
                                        <div id="planFeaturesContainer">
                                            <?php foreach ($product['plan_features'] as $index => $feature): ?>
                                            <div class="feature-item input-group mb-2">
                                                <input type="text" class="form-control" name="plan_features[]" value="<?= htmlspecialchars($feature) ?>">
                                                <button type="button" class="btn btn-danger remove-plan-feature"><i class="bi bi-trash"></i></button>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <button type="button" id="addPlanFeature" class="btn btn-sm btn-primary mt-2">
                                            <i class="bi bi-plus"></i> إضافة ميزة
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="products.php" class="btn btn-secondary">رجوع</a>
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
        
        // تغيير أيقونة الخطة
        $('#plan_type').change(function() {
            const planType = $(this).val();
            let iconClass = '';
            
            switch(planType) {
                case '1': iconClass = 'bi-star-fill basic-plan'; break;
                case '2': iconClass = 'bi-gem popular-plan'; break;
                case '3': iconClass = 'bi-award pro-plan'; break;
                default: iconClass = 'bi-question-circle other-plan';
            }
            
            $('#planIcon').removeClass().addClass('bi ' + iconClass);
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
        
        // إضافة ميزة خطة
        $('#addPlanFeature').click(function() {
            const newFeature = `
                <div class="feature-item input-group mb-2">
                    <input type="text" class="form-control" name="plan_features[]" placeholder="أدخل ميزة الخطة">
                    <button type="button" class="btn btn-danger remove-plan-feature"><i class="bi bi-trash"></i></button>
                </div>
            `;
            $('#planFeaturesContainer').append(newFeature);
        });
        
        // إزالة ميزة (استخدام event delegation)
        $(document).on('click', '.remove-feature, .remove-plan-feature', function() {
            $(this).closest('.feature-item').remove();
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
            
            $('input[name="plan_features[]"]').each(function() {
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
                dataType: 'json', // أضف هذا السطر لتحديد نوع البيانات المتوقعة
                success: function(result) { // تم تعديل المعلمة من response إلى result مباشرة
                    if (result.success) {
                        alert(result.message);
                        window.location.href = '../admin/dashboard.php';
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