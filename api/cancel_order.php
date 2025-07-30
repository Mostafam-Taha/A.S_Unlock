<?php
// بداية الجلسة إذا لزم الأمر
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// إرسال رأس JSON أولاً
header('Content-Type: application/json');

// التحقق من أن الطلب POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'طريقة الطلب غير مسموح بها']);
    exit;
}

// قراءة بيانات JSON المرسلة
$input = json_decode(file_get_contents('php://input'), true);

// إذا كان هناك خطأ في تحليل JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'بيانات غير صالحة']);
    exit;
}

// التحقق من وجود البيانات المطلوبة
if (empty($input['order_id']) || empty($input['reason'])) {
    echo json_encode(['success' => false, 'message' => 'بيانات ناقصة']);
    exit;
}

// الاتصال بقاعدة البيانات
require_once '../includes/config.php';

try {
    // تحديث حالة الطلب في قاعدة البيانات
    $stmt = $pdo->prepare("UPDATE orders SET 
        status = 'rejected',
        cancel_reason = :reason,
        cancelled_at = NOW(),
        updated_at = NOW()
        WHERE id = :order_id");
    
    $stmt->execute([
        ':reason' => htmlspecialchars($input['reason']),
        ':order_id' => $input['order_id']
    ]);
    
    if ($stmt->rowCount() > 0) {
        // إرسال إشعار Telegram
        $telegramToken = '8403544536:AAHqqOWipI-PXZ0e3Ndy_H28x2gX50ldOeQ';
        $chatId = '@asorders';
        
        $message = "🚨 تم إلغاء الطلب #{$input['order_id']}\n";
        $message .= "📝 سبب الإلغاء: {$input['reason']}\n";
        $message .= "🕒 وقت الإلغاء: " . date('Y-m-d H:i:s');
        
        $telegramUrl = "https://api.telegram.org/bot{$telegramToken}/sendMessage";
        $postData = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML'
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $telegramUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $telegramResponse = curl_exec($ch);
        curl_close($ch);
        
        // يمكنك تسجيل رد Telegram إذا لزم الأمر
        // error_log('Telegram response: ' . $telegramResponse);
        
        echo json_encode([
            'success' => true, 
            'message' => 'تم إلغاء الطلب بنجاح وإرسال الإشعار',
            'order_id' => $input['order_id']
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'لم يتم العثور على الطلب أو حدث خطأ أثناء الإلغاء'
        ]);
    }
} catch (PDOException $e) {
    // تسجيل الخطأ في ملف السجل بدلاً من عرضه للمستخدم
    error_log('Database error: ' . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'حدث خطأ في النظام، يرجى المحاولة لاحقاً'
    ]);
} catch (Exception $e) {
    error_log('General error: ' . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'حدث خطأ غير متوقع'
    ]);
}

exit;