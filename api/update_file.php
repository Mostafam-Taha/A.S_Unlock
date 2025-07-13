<?php
header('Content-Type: application/json');
require_once '../includes/config.php';

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('طريقة الطلب غير مسموحة');
    }

    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        $data = $_POST;
    }

    if (!isset($data['id']) || empty($data['id'])) {
        throw new Exception('معرف الملف مطلوب');
    }

    $id = (int)$data['id'];
    
    // التحقق مما إذا كان الملف موجودًا
    $stmt = $pdo->prepare("SELECT * FROM uploads WHERE id = ?");
    $stmt->execute([$id]);
    $file = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$file) {
        throw new Exception('الملف غير موجود');
    }
    
    $isLink = $file['link_url'] !== null;
    
    // إعداد بيانات التحديث
    $updateData = [];
    $params = [];
    
    if (isset($data['name']) && !empty($data['name'])) {
        if ($isLink) {
            $updateData[] = 'link_name = ?';
            $params[] = $data['name'];
        } else {
            $updateData[] = 'file_name = ?';
            $params[] = $data['name'];
        }
    }
    
    if (isset($data['description'])) {
        if ($isLink) {
            $updateData[] = 'link_description = ?';
            $params[] = $data['description'];
        } else {
            $updateData[] = 'description = ?';
            $params[] = $data['description'];
        }
    }
    
    if ($isLink) {
        if (isset($data['link_url']) && !empty($data['link_url'])) {
            $updateData[] = 'link_url = ?';
            $params[] = $data['link_url'];
        }
        
        if (isset($data['link_size'])) {
            $updateData[] = 'link_size = ?';
            $params[] = $data['link_size'] ? (int)$data['link_size'] : null;
        }
    }
    
    if (empty($updateData)) {
        throw new Exception('لا توجد بيانات لتحديثها');
    }
    
    $params[] = $id;
    
    $sql = "UPDATE uploads SET " . implode(', ', $updateData) . " WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    $response['success'] = true;
    $response['message'] = 'تم تحديث البيانات بنجاح';
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>