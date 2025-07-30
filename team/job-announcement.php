<?php
require_once '../includes/config.php';

// التحقق من وجود معرف الوظيفة في URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: team.php");
    exit();
}

$job_id = intval($_GET['id']);

// استعلام للحصول على بيانات الوظيفة مع التحقق من النشر
try {
    $stmt = $pdo->prepare("SELECT * FROM jobs WHERE id = ? AND is_published = 1");
    $stmt->execute([$job_id]);
    $job = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$job) {
        header("Location: job-announcement.php");
        exit();
    }
} catch (PDOException $e) {
    die("Error fetching job details: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($job['title']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --br-primary-color: linear-gradient(90deg, #1976D2, #42A5F5);
            --br-primary-solid: #1976D2;
            --br-color-h-p: #1976D2;
            --br-sacn-color: #23234a;
            --br-links-color: #495057;
            --br-border-color: #e0e0e0;
            --br-btn-padding: 7px 22px;
            --br-box-shadow: none;
            --br-dir-none: none;
            --br-font-w-text: 400;
            --br-matgin-width: 0 100px;
            --br-bg-light: #f5f7fa;
            --br-text-light: #666;
        }

        * {
            margin: 0;
            padding: 3px;
            font-family: "Tajawal", sans-serif;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: "Tajawal", sans-serif;
            background-color: white;
            color: var(--br-sacn-color);
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, h6 {
            color: var(--br-sacn-color);
            font-weight: 600;
        }

        p {
            color: var(--br-sacn-color);
        }

        a {
            text-decoration: none;
            color: var(--br-sacn-color);
        }

        .job-header {
            background: white;
            padding: 3rem 1.5rem 2rem;
            text-align: center;
            border-bottom: 1px solid var(--br-border-color);
        }

        .job-header h1 {
            font-size: 2.2rem;
            margin-bottom: 1rem;
            color: var(--br-primary-solid);
            font-weight: 700;
        }

        .job-header p {
            font-size: 1.1rem;
            color: var(--br-text-light);
            max-width: 700px;
            margin: 0 auto;
        }

        .job-container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }

        .job-card {
            background: white;
            border-radius: 0;
            padding: 0;
            margin-bottom: 3rem;
        }

        .job-title {
            font-size: 1.6rem;
            color: var(--br-sacn-color);
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--br-border-color);
            display: flex;
            align-items: center;
            font-weight: 600;
        }

        .job-title i {
            margin-left: 12px;
            color: var(--br-primary-solid);
        }

        .job-section {
            margin-bottom: 2.5rem;
        }

        .job-section h2 {
            font-size: 1.3rem;
            margin-bottom: 1.2rem;
            color: var(--br-sacn-color);
            display: flex;
            align-items: center;
            font-weight: 600;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--br-border-color);
        }

        .job-section h2 i {
            margin-left: 10px;
            color: var(--br-primary-solid);
            font-size: 1.1rem;
        }

        .job-details {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2.5rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            padding: 0.8rem;
            border: 1px solid var(--br-border-color);
            border-radius: 15px;
            background: white;
            transition: all 0.2s ease;
        }

        .detail-item:hover {
            border-color: var(--br-primary-solid);
        }

        .detail-item i {
            margin-left: 10px;
            color: var(--br-primary-solid);
            font-size: 0.9rem;
        }

        .detail-item span {
            font-size: 0.95rem;
            color: var(--br-sacn-color);
        }

        .requirements-list {
            list-style-type: none;
            padding-right: 1rem;
        }

        .requirements-list li {
            padding: 0.6rem 0;
            position: relative;
            padding-right: 1.8rem;
            color: var(--br-sacn-color);
            border-bottom: 1px dashed var(--br-border-color);
        }

        .requirements-list li:last-child {
            border-bottom: none;
        }

        .requirements-list li::before {
            content: "";
            position: absolute;
            right: 0;
            top: 1rem;
            width: 8px;
            height: 8px;
            background-color: var(--br-primary-solid);
            border-radius: 50%;
        }

        .deadline {
            background: white;
            padding: 1.2rem;
            border-radius: 15px;
            border: 1px solid var(--br-border-color);
            display: flex;
            align-items: center;
            margin: 2.5rem 0;
            position: relative;
            overflow: hidden;
        }

        .deadline::before {
            content: "";
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--br-primary-solid);
        }

        .deadline i {
            margin-left: 12px;
            color: var(--br-primary-solid);
        }

        .deadline p {
            font-weight: 500;
            color: var(--br-sacn-color);
        }

        .deadline strong {
            color: var(--br-primary-solid);
        }

        .apply-btn-container {
            text-align: center;
            margin-top: 3rem;
        }

        .apply-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--br-primary-solid);
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 15px;
            font-weight: 500;
            transition: all 0.2s ease;
            border: 1px solid var(--br-primary-solid);
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
            max-width: 300px;
        }

        .apply-btn:hover {
            background: white;
            color: var(--br-primary-solid);
        }

        .apply-btn i {
            margin-left: 8px;
            transition: transform 0.2s ease;
        }

        .apply-btn:hover i {
            transform: translateX(-5px);
        }

        footer {
            background: white;
            color: var(--br-text-light);
            text-align: center;
            padding: 2rem 1.5rem;
            border-top: 1px solid var(--br-border-color);
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .job-header {
                padding: 2rem 1rem 1.5rem;
            }
            
            .job-header h1 {
                font-size: 1.8rem;
            }
            
            .job-container {
                padding: 0 1rem;
            }
            
            .job-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="job-header">
        <h1><?php echo htmlspecialchars($job['title']); ?></h1>
        <p><?php echo htmlspecialchars($job['company'] ?? 'انضم إلى فريقنا المتميز'); ?></p>
    </header>

    <div class="job-container">
        <div class="job-card">
            <h1 class="job-title"><i class="fas fa-code"></i> <?php echo htmlspecialchars($job['title']); ?></h1>
            
            <div class="job-details">
                <?php if (!empty($job['location'])): ?>
                <div class="detail-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span><?php echo htmlspecialchars($job['location']); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($job['job_type'])): ?>
                <div class="detail-item">
                    <i class="fas fa-clock"></i>
                    <span><?php echo htmlspecialchars($job['job_type']); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($job['created_at'])): ?>
                <div class="detail-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span>تاريخ النشر: <?php echo date('Y-m-d', strtotime($job['created_at'])); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($job['experience'])): ?>
                <div class="detail-item">
                    <i class="fas fa-user-tie"></i>
                    <span><?php echo htmlspecialchars($job['experience']); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($job['salary'])): ?>
                <div class="detail-item">
                    <i class="fas fa-money-bill-wave"></i>
                    <span><?php echo htmlspecialchars($job['salary']); ?></span>
                </div>
                <?php endif; ?>
            </div>

            <div class="job-section">
                <h2><i class="fas fa-file-alt"></i> نظرة عامة</h2>
                <p>
                    <?php echo nl2br(htmlspecialchars($job['description'])); ?>
                </p>
            </div>

            <?php if (!empty($job['skills'])): ?>
            <div class="job-section">
                <h2><i class="fas fa-check-circle"></i> المهارات المطلوبة</h2>
                <ul class="requirements-list">
                    <?php 
                    $skills = explode("\n", $job['skills']);
                    foreach ($skills as $skill): 
                        if (!empty(trim($skill))):
                    ?>
                    <li><?php echo htmlspecialchars(trim($skill)); ?></li>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </ul>
            </div>
            <?php endif; ?>

            <?php if (!empty($job['benefits'])): ?>
            <div class="job-section">
                <h2><i class="fas fa-plus-circle"></i> المزايا</h2>
                <ul class="requirements-list">
                    <?php 
                    $benefits = explode("\n", $job['benefits']);
                    foreach ($benefits as $benefit): 
                        if (!empty(trim($benefit))):
                    ?>
                    <li><?php echo htmlspecialchars(trim($benefit)); ?></li>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </ul>
            </div>
            <?php endif; ?>

            <?php if (!empty($job['deadline'])): ?>
            <div class="deadline">
                <i class="fas fa-hourglass-end"></i>
                <p>آخر موعد للتقديم: <strong><?php echo date('Y-m-d', strtotime($job['deadline'])); ?></strong></p>
            </div>
            <?php endif; ?>

            <div class="apply-btn-container">
                <a href="submit_application.php?id=<?php echo $job_id; ?>" class="apply-btn">تقديم الآن <i class="fas fa-arrow-left"></i></a>
            </div>
        </div>
    </div>
</body>
</html>