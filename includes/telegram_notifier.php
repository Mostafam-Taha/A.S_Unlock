<?php
// includes/telegram_notifier.php

function sendToTelegram($applicationData) {
    // إعداد رسالة التقرير
    $message = "📌 *طلب توظيف جديد*\n\n";
    $message .= "👤 *الاسم:* " . $applicationData['fullName'] . "\n";
    $message .= "📱 *رقم الهاتف:* " . $applicationData['phoneNumber'] . "\n";
    $message .= "📍 *العنوان:* " . $applicationData['address'] . "\n";
    $message .= "💼 *نوع العمل:* " . ($applicationData['workType'] === 'online' ? 'عمل عن بعد' : 'عمل في موقع') . "\n";
    $message .= "*-----* " . "\n";
    $message .= "🛠 *المهارات:* " . $applicationData['skills'] . "\n";
    
    // إعداد بيانات الطلب إلى Telegram
    $telegramData = [
        'chat_id' => '@asorders',
        'text' => $message,
        'parse_mode' => 'Markdown'
    ];
    
    try {
        // استخدام cURL لإرسال الرسالة
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot8403544536:AAHqqOWipI-PXZ0e3Ndy_H28x2gX50ldOeQ/sendMessage");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $telegramData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    } catch (Exception $e) {
        // يمكن تسجيل الخطأ دون إفشاء معلومات حساسة
        error_log("Telegram notification error: " . $e->getMessage());
        return false;
    }
}
?>