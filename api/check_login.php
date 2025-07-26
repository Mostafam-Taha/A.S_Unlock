<?php
session_start();
require_once '../includes/config.php';

$response = ['loggedIn' => false];

if(isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT email, phone FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($user) {
        $response = [
            'loggedIn' => true,
            'user' => [
                'email' => $user['email'],
                'phone' => $user['phone'] ?? ''
            ]
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>  