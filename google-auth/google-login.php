<?php
header('Content-Type: application/json'); // يجب أن يكون هذا أول سطر

require '../includes/config.php';

// إغلاق أي إخراج غير مرغوب فيه
ob_clean();

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('طريقة الطلب غير مسموحة');
    }

    $token = $_POST['credential'] ?? '';
    
    if (empty($token)) {
        throw new Exception('لم يتم توفير بيانات صالحة');
    }
    
    $tokenParts = explode('.', $token);
    if (count($tokenParts) !== 3) {
        throw new Exception('بيانات التوكن غير صالحة');
    }
    
    $payload = base64_decode($tokenParts[1]);
    $data = json_decode($payload, true);
    
    if (!$data || !isset($data['email'], $data['sub'])) {
        throw new Exception('بيانات غير صالحة');
    }
    
    $email = $data['email'];
    $name = $data['name'] ?? 'مستخدم جديد';
    $google_id = $data['sub'];
    $avatar = $data['picture'] ?? null;
    
    // البحث أو إنشاء المستخدم
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR google_id = ?");
    $stmt->execute([$email, $google_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, google_id, avatar) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $google_id, $avatar]);
        $user_id = $pdo->lastInsertId();
        
        $user = [
            'id' => $user_id,
            'name' => $name,
            'email' => $email
        ];
    }
    
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_name'] = $user['name'];
    
    echo json_encode([
        'success' => true,
        'message' => 'تم تسجيل الدخول بنجاح',
        'user' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email']
        ]
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

exit; // تأكد من عدم وجود أي إخراج بعد هذا السطر
?>