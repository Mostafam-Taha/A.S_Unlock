<?php
require_once '../includes/config.php';

try {
    $sql = "SELECT id, title, category, job_type, is_published, created_at 
            FROM jobs 
            ORDER BY created_at DESC";    $stmt = $pdo->query($sql);
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($jobs as $job) {
        $publishedStatus = $job['is_published'] ? 
            '<span class="badge bg-success">منشور</span>' : 
            '<span class="badge bg-secondary">غير منشور</span>';
        
        $publishAction = $job['is_published'] ?
            '<button class="btn btn-sm btn-warning unpublish-job" data-id="'.$job['id'].'">إخفاء</button>' :
            '<button class="btn btn-sm btn-success publish-job" data-id="'.$job['id'].'">نشر</button>';
        
        echo '<tr>
                <td>'.$job['id'].'</td>
                <td>'.$job['title'].'</td>
                <td>'.$job['category'].'</td>
                <td>'.date('Y-m-d', strtotime($job['created_at'])).'</td>
                <td>'.$publishedStatus.'</td>
                <td>
                    '.$publishAction.'
                    <button class="btn btn-sm btn-primary edit-job" data-id="'.$job['id'].'">تعديل</button>
                    <button class="btn btn-sm btn-danger delete-job" data-id="'.$job['id'].'">حذف</button>
                </td>
              </tr>';
    }
} catch (PDOException $e) {
    echo '<tr><td colspan="6" class="text-danger">خطأ في جلب البيانات: '.$e->getMessage().'</td></tr>';
}