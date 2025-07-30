<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit();
}

// يمكنك إضافة المزيد من التحقق من الصلاحيات هنا