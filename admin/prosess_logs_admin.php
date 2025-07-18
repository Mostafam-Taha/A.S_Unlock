<?php
require_once '../includes/config.php';

session_start();

if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

if (empty($_SESSION['admin_id']) && checkRememberMe($pdo)) {
    header('Location: dashboard.php');
    exit;
}

function checkRememberMe($pdo) {
    if (empty($_COOKIE['remember_token'])) return false;
    
    $stmt = $pdo->prepare("
        SELECT admin_id, username 
        FROM admins 
        WHERE remember_token = ? 
        AND token_expiry > NOW()
    ");
    
    $stmt->execute([$_COOKIE['remember_token']]);
    $admin = $stmt->fetch();
    
    if ($admin) {
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['username'] = $admin['username'];
        return true;
    }
    
    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    try {
        $stmt = $pdo->prepare("
            SELECT admin_id, username, password_hash 
            FROM admins 
            WHERE username = ? 
            AND is_active = 1
        ");
        
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
        
        if (!$admin || !password_verify($password, $admin['password_hash'])) {
            logLoginAttempt($pdo, null, $username, false);
            $error = 'اسم المستخدم أو كلمة المرور غير صحيحة';
            require 'logs.php';
            exit;
        }
        
        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['username'] = $admin['username'];
        
        if ($remember) {
            createRememberMeToken($pdo, $admin['admin_id']);
        }
        
        logLoginAttempt($pdo, $admin['admin_id'], $username, true);
        
        header('Location: dashboard.php');
        exit;
        
    } catch (PDOException $e) {
        $error = 'حدث خطأ في الخادم';
        require 'logs.php';
        exit;
    }
}

require 'logs.php';

function logLoginAttempt($pdo, $adminId, $username, $isSuccess) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    
    $stmt = $pdo->prepare("
        INSERT INTO login_attempts (
            admin_id, 
            username, 
            ip_address, 
            user_agent, 
            is_success
        ) VALUES (?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $adminId,
        $username,
        $ip,
        $userAgent,
        $isSuccess ? 1 : 0
    ]);
}

function createRememberMeToken($pdo, $adminId) {
    $token = bin2hex(random_bytes(32));
    $expiry = date('Y-m-d H:i:s', strtotime('+30 days'));
    
    $stmt = $pdo->prepare("
        UPDATE admins 
        SET remember_token = ?, token_expiry = ?
        WHERE admin_id = ?
    ");
    
    $stmt->execute([$token, $expiry, $adminId]);
    
    // تعيين كوكي لمدة 30 يوم
    setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', true, true);
    setcookie('admin_id', $adminId, time() + (30 * 24 * 60 * 60), '/', '', true, true);
}