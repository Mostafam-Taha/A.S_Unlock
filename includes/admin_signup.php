<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once 'config.php';

function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

function hashSecurityAnswer($answer) {
    return hash('sha256', strtolower(trim($answer)));
}

function isPasswordStrong($password) {
    if (strlen($password) < 8) return false;
    if (!preg_match('/[A-Z]/', $password)) return false;
    if (!preg_match('/[a-z]/', $password)) return false;
    if (!preg_match('/[0-9]/', $password)) return false;
    if (!preg_match('/[\W]/', $password)) return false;
    return true;
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('طريقة الطلب غير مسموح بها', 405);
    }

    $json = file_get_contents('php://input');
    if (empty($json)) {
        throw new Exception('يجب إرسال بيانات JSON', 400);
    }

    $data = json_decode($json, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('بيانات JSON غير صالحة', 400);
    }

    $required = ['username', 'email', 'password', 'security_question_id', 'security_answer'];
    foreach ($required as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            throw new Exception("حقل {$field} مطلوب", 400);
        }
    }

    $username = trim($data['username']);
    $email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
    $password = $data['password'];
    $securityQuestionId = (int)$data['security_question_id'];
    $securityAnswer = trim($data['security_answer']);

    if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        throw new Exception('اسم المستخدم يجب أن يحتوي على أحرف إنجليزية وأرقام فقط', 400);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('البريد الإلكتروني غير صالح', 400);
    }

    if (!isPasswordStrong($password)) {
        throw new Exception('كلمة المرور يجب أن تحتوي على 8 أحرف على الأقل، حرف كبير، حرف صغير، رقم، ورمز خاص', 400);
    }

    if ($securityQuestionId < 1 || $securityQuestionId > 5) {
        throw new Exception('سؤال الأمان غير صالح', 400);
    }

    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM admins WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetchColumn() > 0) {
        throw new Exception('اسم المستخدم أو البريد الإلكتروني موجود مسبقاً', 409);
    }

    $passwordHash = hashPassword($password);
    $securityAnswerHash = hashSecurityAnswer($securityAnswer);

    $stmt = $pdo->prepare("
        INSERT INTO admins (
            username, 
            email, 
            password_hash, 
            security_question_id, 
            security_answer_hash,
            permissions
        ) VALUES (?, ?, ?, ?, ?, ?)
    ");

    $defaultPermissions = json_encode([
        'manage_users' => true,
        'manage_content' => true,
        'manage_settings' => true,
        'access_reports' => true
    ]);

    $stmt->execute([
        $username,
        $email,
        $passwordHash,
        $securityQuestionId,
        $securityAnswerHash,
        $defaultPermissions
    ]);

    $pdo->commit();

    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'تم إنشاء حساب المسؤول بنجاح',
        'admin_id' => $pdo->lastInsertId()
    ]);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'خطأ في قاعدة البيانات: ' . $e->getMessage(),
        'error_code' => $e->getCode()
    ]);
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error_code' => $e->getCode() ?: 400
    ]);
}