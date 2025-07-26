<?php
include '../includes/config.php';

function safeOutput($value, $default = 'غير محدد') {
    return $value !== null ? htmlspecialchars($value) : $default;
}

if (isset($_GET['id'])) {
    $userId = intval($_GET['id']);
    
    try {
        // جلب بيانات المستخدم
        $userQuery = "SELECT * FROM users WHERE id = :id";
        $userStmt = $pdo->prepare($userQuery);
        $userStmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $userStmt->execute();
        
        if ($userStmt->rowCount() > 0) {
            $user = $userStmt->fetch(PDO::FETCH_ASSOC);
            
            // جلب طلبات المستخدم
            $ordersQuery = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC";
            $ordersStmt = $pdo->prepare($ordersQuery);
            $ordersStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $ordersStmt->execute();
            $orders = $ordersStmt->fetchAll(PDO::FETCH_ASSOC);
            
            // عرض بيانات المستخدم
            echo '<div class="user-details mb-4">';
            echo '<h4>معلومات المستخدم</h4>';
            echo '<div class="row">';
            echo '<div class="col-md-6">';
            echo '<p><strong>ID:</strong> ' . safeOutput($user['id']) . '</p>';
            echo '<p><strong>الاسم:</strong> ' . safeOutput($user['name']) . '</p>';
            echo '<p><strong>البريد الإلكتروني:</strong> ' . safeOutput($user['email']) . '</p>';
            echo '</div>';
            echo '<div class="col-md-6">';
            echo '<p><strong>الهاتف:</strong> ' . safeOutput($user['phone']) . '</p>';
            echo '<p><strong>تاريخ الإنشاء:</strong> ' . safeOutput($user['created_at']) . '</p>';
            echo '<p><strong>حالة الحساب:</strong> ' . ($user['verified'] ? 'مفعل' : 'غير مفعل') . '</p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            
            // عرض طلبات المستخدم
            echo '<div class="user-orders">';
            echo '<h4>طلبات المستخدم</h4>';
            
            if (count($orders) > 0) {
                echo '<div class="table-responsive">';
                echo '<table class="table table-bordered table-striped">';
                echo '<thead class="thead-dark">';
                echo '<tr>';
                echo '<th>رقم الطلب</th>';
                echo '<th>المنتج</th>';
                echo '<th>الخطة</th>';
                echo '<th>طريقة الدفع</th>';
                echo '<th>المبلغ</th>';
                echo '<th>الحالة</th>';
                echo '<th>تاريخ الطلب</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                
                foreach ($orders as $order) {
                    echo '<tr>';
                    echo '<td>' . safeOutput($order['id']) . '</td>';
                    echo '<td>' . safeOutput($order['product_id']) . '</td>';
                    echo '<td>' . safeOutput($order['plan_id']) . '</td>';
                    echo '<td>' . safeOutput($order['payment_method']) . '</td>';
                    echo '<td>' . safeOutput($order['amount']) . ' جنيه</td>';
                    echo '<td>';
                    
                    // تلوين حالة الطلب حسب الحالة
                    $statusClass = '';
                    switch ($order['status']) {
                        case 'verified': $statusClass = 'text-success'; break;
                        case 'rejected': $statusClass = 'text-danger'; break;
                        case 'completed': $statusClass = 'text-primary'; break;
                        default: $statusClass = 'text-warning';
                    }
                    
                    echo '<span class="' . $statusClass . '">' . safeOutput($order['status']) . '</span>';
                    echo '</td>';
                    echo '<td>' . safeOutput($order['created_at']) . '</td>';
                    echo '</tr>';
                }
                
                echo '</tbody>';
                echo '</table>';
                echo '</div>';
            } else {
                echo '<div class="alert alert-info">لا يوجد طلبات لهذا المستخدم</div>';
            }
            
            echo '</div>';
        } else {
            echo '<p>لم يتم العثور على المستخدم</p>';
        }
    } catch (PDOException $e) {
        echo '<p>حدث خطأ في جلب البيانات: ' . safeOutput($e->getMessage()) . '</p>';
    }
} else {
    echo '<p>لم يتم تحديد مستخدم</p>';
}
?>