<?php
require_once '../includes/config.php';

session_start();

// مسح جلسة المستخدم
session_unset();
session_destroy();

// مسح كوكيز تذكرني
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/');
    setcookie('admin_id', '', time() - 3600, '/');
    
    // لو حابيت مسح token من قاعدة البيانات اتصل عليا +20 100 350 4114 
}

header('Location: logs.php');
exit;