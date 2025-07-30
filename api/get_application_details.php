<?php
require_once '../includes/config.php';

if (!isset($_GET['id'])) {
    die('معرف الطلب غير موجود');
}

$id = $_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM job_applications WHERE id = ?");
    $stmt->execute([$id]);
    $app = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$app) {
        die('الطلب غير موجود');
    }
} catch (PDOException $e) {
    die('خطأ في قاعدة البيانات: ' . $e->getMessage());
}
?>

<div class="row">
    <div class="col-md-6">
        <h5>المعلومات الشخصية</h5>
        <p><strong>الاسم الكامل:</strong> <?= htmlspecialchars($app['full_name']) ?></p>
        <p><strong>رقم الهاتف:</strong> <?= htmlspecialchars($app['phone_number']) ?></p>
        <p><strong>العنوان:</strong> <?= htmlspecialchars($app['address']) ?></p>
    </div>
    <div class="col-md-6">
        <h5>معلومات العمل</h5>
        <p><strong>نوع العمل:</strong> <?= $app['work_type'] === 'online' ? 'عن بعد' : 'في الموقع' ?></p>
        <p><strong>البلد:</strong> <?= htmlspecialchars($app['country_name'] ?? '--') ?></p>
        <p><strong>المدينة:</strong> <?= htmlspecialchars($app['city_name'] ?? '--') ?></p>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <h5>المهارات</h5>
        <p><?= nl2br(htmlspecialchars($app['skills'])) ?></p>
    </div>
</div>

<?php if (!empty($app['profile_image_path'])): ?>
<div class="row mt-3">
    <div class="col-12">
        <h5>الصورة الشخصية</h5>
        <img src="../profile_images/<?= htmlspecialchars($app['profile_image_path']) ?>" class="img-thumbnail" style="max-height: 200px;">
    </div>
</div>
<?php endif; ?>