<?php
session_start();
require_once '../includes/config.php';
define('PROTECTED_ACCESS', true);

if (!isset($_SESSION['authenticated'])) {
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied');
}

header('Content-Type: application/json');
echo json_encode([
    'emailjs_user_id' => EMAILJS_USER_ID,
    'emailjs_service_id' => EMAILJS_SERVICE_ID,
    'emailjs_template_id' => EMAILJS_TEMPLATE_ID,
    'telegram_bot_token' => TELEGRAM_BOT_TOKEN,
    'telegram_chat_id' => TELEGRAM_CHAT_ID
]);
?>