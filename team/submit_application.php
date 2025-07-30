
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نموذج التقديم للوظيفة</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --br-primary-color: linear-gradient(90deg, #1976D2, #42A5F5);
            --br-secondary-color: #f8f9fa;
            --br-color-h-p: #1976D2;
            --br-sacn-color: #23234a;
            --br-links-color: #495057;
            --br-border-color: #dfe1e5;
            --br-btn-padding: 7px 22px;
            --br-box-shadow: 0px 0px 0px 5px #1976d254;
            --br-card-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
            --br-success-color: #4CAF50;
            --br-error-color: #d32f2f;
            --br-transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Tajawal", sans-serif;
        }

        body {
            background-color: #f8fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            color: var(--br-sacn-color);
            line-height: 1.6;
        }

        .application-container {
            width: 100%;
            max-width: 850px;
            background: white;
            border-radius: 18px;
            box-shadow: var(--br-card-shadow);
            overflow: hidden;
            position: relative;
            margin: 30px 0;
        }

        .application-container::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 6px;
            background: var(--br-primary-color);
        }

        .header {
            padding: 35px 40px 25px;
            text-align: center;
            background: linear-gradient(135deg, #f5f9ff 0%, #e6f0ff 100%);
        }

        .header h1 {
            font-size: 34px;
            color: var(--br-color-h-p);
            margin-bottom: 12px;
            position: relative;
            display: inline-block;
            font-weight: 700;
        }

        .header h1::after {
            content: '';
            position: absolute;
            bottom: -10px;
            right: 50%;
            transform: translateX(50%);
            width: 90px;
            height: 4px;
            background: var(--br-primary-color);
            border-radius: 2px;
        }

        .header p {
            color: var(--br-links-color);
            font-size: 16px;
            max-width: 80%;
            margin: 0 auto;
        }

        .form-content {
            padding: 30px 40px 40px;
        }

        .form-section {
            margin-bottom: 35px;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .section-title {
            font-size: 20px;
            color: var(--br-color-h-p);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--br-border-color);
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -1px;
            right: 0;
            width: 100px;
            height: 2px;
            background: var(--br-primary-color);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 12px;
            font-weight: 500;
            color: var(--br-sacn-color);
            font-size: 16px;
        }

        .form-control {
            width: 100%;
            padding: 15px 20px;
            border: 1px solid var(--br-border-color);
            border-radius: 12px;
            font-size: 16px;
            transition: var(--br-transition);
            background-color: #f9fafc;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--br-color-h-p);
            box-shadow: var(--br-box-shadow);
            background-color: white;
        }

        textarea.form-control {
            min-height: 130px;
            resize: vertical;
        }

        .radio-group {
            display: flex;
            gap: 30px;
            margin-top: 15px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: var(--br-transition);
        }

        .radio-option:hover {
            transform: translateY(-2px);
        }

        .radio-option input {
            width: 20px;
            height: 20px;
            accent-color: var(--br-color-h-p);
            cursor: pointer;
        }

        .radio-option label {
            margin-bottom: 0;
            cursor: pointer;
            font-size: 15px;
        }

        .file-upload {
            position: relative;
            overflow: hidden;
            width: 100%;
            border-radius: 12px;
        }

        .file-upload-btn {
            border: 2px dashed var(--br-border-color);
            border-radius: 12px;
            padding: 35px;
            text-align: center;
            cursor: pointer;
            transition: var(--br-transition);
            background-color: #f9fafc;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .file-upload-btn:hover {
            border-color: var(--br-color-h-p);
            background-color: rgba(25, 118, 210, 0.05);
            transform: translateY(-2px);
        }

        .file-upload-icon {
            font-size: 28px;
            color: var(--br-color-h-p);
        }

        .file-upload-input {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .preview-image {
            max-width: 160px;
            max-height: 160px;
            margin-top: 20px;
            border-radius: 10px;
            display: none;
            border: 2px solid var(--br-border-color);
            object-fit: cover;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .submit-btn {
            background: var(--br-primary-color);
            color: white;
            border: none;
            padding: 18px;
            font-size: 18px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 500;
            transition: var(--br-transition);
            width: 100%;
            margin-top: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            box-shadow: 0 4px 15px rgba(25, 118, 210, 0.2);
        }

        .submit-btn:hover {
            opacity: 0.95;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(25, 118, 210, 0.3);
        }

        .skills-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }

        .skill-tag {
            background-color: #e3f2fd;
            color: var(--br-color-h-p);
            padding: 10px 16px;
            border-radius: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: var(--br-transition);
        }

        .skill-tag:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .remove-skill {
            cursor: pointer;
            font-size: 12px;
            color: var(--br-color-h-p);
            transition: var(--br-transition);
        }

        .remove-skill:hover {
            transform: scale(1.2);
        }

        .summary-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 10000;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(5px);
            animation: fadeIn 0.3s ease;
        }

        .summary-modal-content {
            background: white;
            padding: 35px;
            border-radius: 18px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
            position: relative;
            border: 1px solid rgba(25, 118, 210, 0.2);
        }

        .summary-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--br-border-color);
        }

        .summary-modal-title {
            color: var(--br-color-h-p);
            font-size: 26px;
            font-weight: 600;
        }

        .summary-modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: var(--br-links-color);
            transition: var(--br-transition);
        }

        .summary-modal-close:hover {
            color: var(--br-color-h-p);
            transform: rotate(90deg);
        }

        .summary-section {
            margin-bottom: 25px;
            animation: fadeIn 0.5s ease;
        }

        .summary-section-title {
            color: var(--br-color-h-p);
            margin-bottom: 15px;
            font-size: 20px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--br-border-color);
            position: relative;
        }

        .summary-section-title::after {
            content: '';
            position: absolute;
            bottom: -1px;
            right: 0;
            width: 80px;
            height: 2px;
            background: var(--br-primary-color);
        }

        .summary-row {
            display: flex;
            margin-bottom: 12px;
        }

        .summary-label {
            width: 180px;
            font-weight: 500;
            color: var(--br-sacn-color);
            flex-shrink: 0;
        }

        .summary-value {
            flex: 1;
            color: var(--br-links-color);
            word-break: break-word;
        }

        .summary-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 35px;
            flex-wrap: wrap;
        }

        .summary-btn {
            padding: 14px 28px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 500;
            transition: var(--br-transition);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            border: none;
        }

        .summary-btn.print {
            background: var(--br-primary-color);
            color: white;
            box-shadow: 0 4px 15px rgba(25, 118, 210, 0.2);
        }

        .summary-btn.print:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(25, 118, 210, 0.3);
        }

        .summary-btn.close {
            background: #f5f5f5;
            color: var(--br-links-color);
        }

        .summary-btn.close:hover {
            background: #e9ecef;
        }

        @media (max-width: 768px) {
            .header, .form-content {
                padding: 25px;
            }
            
            .header h1 {
                font-size: 28px;
            }
            
            .header p {
                max-width: 100%;
            }
            
            .radio-group {
                flex-direction: column;
                gap: 15px;
            }
            
            .summary-modal-content {
                padding: 25px;
            }
            
            .summary-row {
                flex-direction: column;
                gap: 5px;
            }
            
            .summary-label {
                width: 100%;
            }
            
            .summary-actions {
                flex-direction: column;
            }
            
            .summary-btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media print {
            body * {
                visibility: hidden;
            }
            .summary-modal, .summary-modal * {
                visibility: visible;
            }
            .summary-modal {
                position: absolute;
                left: 0;
                right: 0;
                top: 0;
                bottom: 0;
                width: 100%;
                height: auto;
                background: white;
                padding: 0;
                margin: 0;
            }
            .summary-modal-content {
                box-shadow: none;
                border: none;
                width: 100%;
                max-width: 100%;
                padding: 20px;
            }
            .summary-actions {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div id="summaryModal" class="summary-modal">
        <div class="summary-modal-content">
            <div class="summary-modal-header">
                <h2 class="summary-modal-title">استمارة التقديم للوظيفة</h2>
                <button class="summary-modal-close" id="closeSummaryModal">&times;</button>
            </div>
            
            <div id="summaryContent"></div>
            
            <div class="summary-actions">
                <button class="summary-btn print" id="printSummary">
                    <i class="fas fa-print"></i> طباعة الاستمارة
                </button>
                <button class="summary-btn close" id="closeSummaryBtn">
                    <i class="fas fa-times"></i> إغلاق
                </button>
            </div>
        </div>
    </div>

    <div class="application-container">
        <div class="header">
            <h1>تقديم على وظيفة</h1>
            <p>املأ النموذج التالي لتقديم طلبك للوظيفة</p>
        </div>

        <div class="form-content">
            <form id="jobApplicationForm" enctype="multipart/form-data">
                <div class="form-section">
                    <h3 class="section-title">المعلومات الشخصية</h3>
                    
                    <div class="form-group">
                        <label for="fullName" class="form-label">الاسم الكامل</label>
                        <input type="text" id="fullName" class="form-control" required placeholder="الاسم الثلاثي">
                    </div>
                    
                    <div class="form-group">
                        <label for="phoneNumber" class="form-label">رقم الهاتف (واتساب)</label>
                        <input type="tel" id="phoneNumber" class="form-control" required></div>
                    
                    <div class="form-group">
                        <label for="address" class="form-label">موقعك بالتفصيل</label>
                        <textarea id="address" class="form-control" required></textarea>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">معلومات العمل</h3>
                    
                    <div class="form-group">
                        <label class="form-label">نوع العمل</label>
                        <div class="radio-group">
                            <div class="radio-option">
                                <input type="radio" id="workTypeOnline" name="workType" value="online" required checked>
                                <label for="workTypeOnline">
                                    <i class="fas fa-laptop-house"></i> عمل عن بعد
                                </label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" id="workTypeOffline" name="workType" value="offline">
                                <label for="workTypeOffline">
                                    <i class="fas fa-building"></i> عمل في موقع
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">المهارات</h3>
                    
                    <div class="form-group">
                        <label for="skills" class="form-label">المهارات التي تمتلكها</label>
                        <textarea id="skills" class="form-control" placeholder="اكتب مهاراتك ثم اضغط Enter أو الفاصلة"></textarea>
                        <div class="skills-tags" id="skillsTags"></div>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title">الصورة الشخصية</h3>
                    
                    <div class="form-group">
                        <div class="file-upload">
                            <div class="file-upload-btn" id="uploadBtn">
                                <i class="fas fa-cloud-upload-alt file-upload-icon"></i>
                                <p>اضغط لرفع صورتك الشخصية</p>
                                <small>الحجم الأقصى: 5MB - الصيغ: JPG, PNG</small>
                            </div>
                            <input type="file" id="profileImage" class="file-upload-input" accept="image/jpeg, image/png">
                            <img id="imagePreview" class="preview-image" alt="معاينة الصورة">
                        </div>
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-paper-plane"></i> تقديم الطلب
                </button>
            </form>
        </div>
    </div>

    <!-- <script src="../assets/js/submit_application.js"></script> -->
    <script src="../assets/js/app.js"></script>
</body>
</html>