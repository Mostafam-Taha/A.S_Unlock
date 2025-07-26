<?php
require_once '../includes/config.php';

// استعلام لاسترجاع جميع محاولات تسجيل الدخول
try {
    $stmt = $pdo->query("SELECT * FROM login_attempts ORDER BY attempt_time DESC");
    $attempts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching login attempts: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>محاولات تسجيل الدخول</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tajawal Font -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --br-primary-color: linear-gradient(90deg, #1976D2, #42A5F5);
            --br-color-h-p: #1976D2;
            --br-sacn-color: #23234a;
            --br-links-color: #495057;
            --br-border-color: #dfe1e5;
            --br-btn-padding: 7px 22px;
            --br-box-shadow: 0px 0px 0px 5px #1976d254;
            --br-dir-none: none;
            --br-font-w-text: 400;
            --br-matgin-width: 0 100px;
        }

        body {
            font-family: "Tajawal", sans-serif;
            background-color: #f5f7fa;
        }

        .table-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }

        .table-hover tbody tr:hover {
            cursor: pointer;
            background-color: rgba(25, 118, 210, 0.05);
        }

        .status-success {
            color: #28a745;
            font-weight: 500;
        }

        .status-failed {
            color: #dc3545;
            font-weight: 500;
        }

        .detail-label {
            font-weight: bold;
            color: var(--br-sacn-color);
            min-width: 120px;
        }

        .detail-value {
            color: #495057;
        }

        .modal-header {
            background: var(--br-primary-color);
            color: white;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0" style="color: var(--br-color-h-p);">محاولات تسجيل الدخول</h1>
        </div>

        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>اسم المستخدم</th>
                            <th>عنوان IP</th>
                            <th>نوع الجهاز</th>
                            <th>التاريخ</th>
                            <th>الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attempts as $attempt): ?>
                        <tr data-bs-toggle="modal" data-bs-target="#detailsModal" 
                            data-details='<?php echo json_encode($attempt); ?>'>
                            <td><?php echo htmlspecialchars($attempt['attempt_id']); ?></td>
                            <td><?php echo htmlspecialchars($attempt['username']); ?></td>
                            <td><?php echo htmlspecialchars($attempt['ip_address']); ?></td>
                            <td>
                                <?php 
                                $device = htmlspecialchars($attempt['device_model'] ?? 'غير معروف');
                                echo strlen($device) > 20 ? substr($device, 0, 20) . '...' : $device;
                                ?>
                            </td>
                            <td><?php echo date('Y-m-d H:i', strtotime($attempt['attempt_time'])); ?></td>
                            <td>
                                <?php if ($attempt['is_success']): ?>
                                    <span class="status-success">ناجح</span>
                                <?php else: ?>
                                    <span class="status-failed">فاشل</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for Details -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">تفاصيل محاولة الدخول</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="d-flex mb-2">
                                <span class="detail-label">رقم المحاولة:</span>
                                <span class="detail-value" id="detail-id"></span>
                            </div>
                            <div class="d-flex mb-2">
                                <span class="detail-label">اسم المستخدم:</span>
                                <span class="detail-value" id="detail-username"></span>
                            </div>
                            <div class="d-flex mb-2">
                                <span class="detail-label">عنوان IP:</span>
                                <span class="detail-value" id="detail-ip"></span>
                            </div>
                            <div class="d-flex mb-2">
                                <span class="detail-label">الحالة:</span>
                                <span class="detail-value" id="detail-status"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex mb-2">
                                <span class="detail-label">تاريخ المحاولة:</span>
                                <span class="detail-value" id="detail-time"></span>
                            </div>
                            <div class="d-flex mb-2">
                                <span class="detail-label">نظام التشغيل:</span>
                                <span class="detail-value" id="detail-os"></span>
                            </div>
                            <div class="d-flex mb-2">
                                <span class="detail-label">المتصفح:</span>
                                <span class="detail-value" id="detail-agent"></span>
                            </div>
                            <div class="d-flex mb-2">
                                <span class="detail-label">الموقع:</span>
                                <span class="detail-value" id="detail-location"></span>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">مواصفات الجهاز</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="d-flex mb-2">
                                        <span class="detail-label">النوع:</span>
                                        <span class="detail-value" id="detail-model"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex mb-2">
                                        <span class="detail-label">المعالج:</span>
                                        <span class="detail-value" id="detail-processor"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex mb-2">
                                        <span class="detail-label">الذاكرة:</span>
                                        <span class="detail-value" id="detail-ram"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex mb-2">
                                        <span class="detail-label">التخزين:</span>
                                        <span class="detail-value" id="detail-storage"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex mb-2">
                                        <span class="detail-label">حالة البطارية:</span>
                                        <span class="detail-value" id="detail-battery"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap & jQuery JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#detailsModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var details = button.data('details');
                var modal = $(this);

                // Basic Info
                modal.find('#detail-id').text(details.attempt_id);
                modal.find('#detail-username').text(details.username);
                modal.find('#detail-ip').text(details.ip_address);
                modal.find('#detail-time').text(new Date(details.attempt_time).toLocaleString());
                
                // Status
                if(details.is_success) {
                    modal.find('#detail-status').html('<span class="status-success">ناجح</span>');
                } else {
                    modal.find('#detail-status').html('<span class="status-failed">ساقط يا أبو عمو</span>');
                }

                // Device Info
                modal.find('#detail-model').text(details.device_model || 'غير معروف');
                modal.find('#detail-os').text(details.os_version || 'غير معروف');
                modal.find('#detail-agent').text(details.user_agent || 'غير معروف');
                modal.find('#detail-processor').text(details.processor || 'غير معروف');
                modal.find('#detail-ram').text(details.ram || 'غير معروف');
                modal.find('#detail-storage').text(details.storage || 'غير معروف');
                modal.find('#detail-battery').text(details.battery_status || 'غير معروف');

                // Location
                if(details.location_coords) {
                    modal.find('#detail-location').html(
                        '<a href="https://maps.google.com/?q=' + details.location_coords + '" target="_blank">عرض على الخريطة</a>'
                    );
                } else {
                    modal.find('#detail-location').text('غير متاح');
                }
            });
        });
    </script>
</body>
</html>