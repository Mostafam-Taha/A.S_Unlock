$(document).ready(function() {
    // دالة لتحديد أيقونة الملف حسب النوع
    function getFileIcon(fileType, isLink = false) {
        if (isLink) {
            return 'bi-link-45deg';
        }
        
        const type = fileType.split('/')[0];
        const extension = fileType.split('/').pop().toLowerCase();
        
        const iconMap = {
            'image': 'bi-file-image',
            'audio': 'bi-file-music',
            'video': 'bi-file-play',
            'application': {
                'pdf': 'bi-filetype-pdf',
                'msword': 'bi-filetype-doc',
                'vnd.openxmlformats-officedocument.wordprocessingml.document': 'bi-filetype-docx',
                'vnd.ms-excel': 'bi-filetype-xls',
                'vnd.openxmlformats-officedocument.spreadsheetml.sheet': 'bi-filetype-xlsx',
                'vnd.ms-powerpoint': 'bi-filetype-ppt',
                'vnd.openxmlformats-officedocument.presentationml.presentation': 'bi-filetype-pptx',
                'zip': 'bi-file-zip',
                'x-rar-compressed': 'bi-file-zip',
                'x-7z-compressed': 'bi-file-zip',
                'octet-stream': 'bi-file-binary',
                'json': 'bi-filetype-json',
                'rar': 'bi-file-medical-fill',
                'x-empty': 'bi-filetype-exe',
                'x-dosexec': 'bi-filetype-exe'
            },
            'text': 'bi-file-text'
        };
        
        if (iconMap[type] && typeof iconMap[type] === 'object') {
            return iconMap[type][extension] || 'bi-file-earmark';
        }
        
        return iconMap[type] || 'bi-file-earmark';
    }
    
    // جلب الملفات من الخادم
    function loadFiles() {
        $.get('../api/get_files.php', function(response) {
            if (response.success) {
                renderFiles(response.data);
            } else {
                console.error('Failed to load files:', response.message);
            }
        }, 'json');
    }
    
    // جلب تفاصيل ملف معين
    function getFileDetails(fileId) {
        $.get('../api/get_file_details.php', {id: fileId}, function(response) {
            if (response.success) {
                showFileDetails(response.data);
            } else {
                console.error('Failed to load file details:', response.message);
                alert('فشل في تحميل تفاصيل الملف');
            }
        }, 'json');
    }
    
    // عرض تفاصيل الملف في نافذة
    function showFileDetails(file) {
        const isLink = file.link_url !== null;
        const iconClass = getFileIcon(isLink ? 'link' : file.file_type, isLink);
        
        // تنسيق حجم الملف إذا كان ملفًا وليس رابطًا
        const fileSize = isLink ? 
            (file.link_size ? file.link_size + ' MB' : 'غير محدد') : 
            formatFileSize(file.file_size);
        
        // إنشاء محتوى نافذة التفاصيل
        const detailsContent = `
            <div class="file-details-modal">
                <div class="modal-content">
                    <span class="close-modal">&times;</span>
                    <div class="file-header">
                        <i class="bi ${iconClass}"></i>
                        <h2>${isLink ? file.link_name : file.file_name}</h2>
                    </div>
                    <div class="file-info">
                        <div class="info-row">
                            <span class="info-label">النوع:</span>
                            <span class="info-value">${isLink ? 'رابط خارجي' : file.file_type}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">الحجم:</span>
                            <span class="info-value">${fileSize}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">تاريخ الرفع:</span>
                            <span class="info-value">${new Date(file.upload_date).toLocaleString()}</span>
                        </div>
                        ${file.description ? `
                        <div class="info-row">
                            <span class="info-label">الوصف:</span>
                            <span class="info-value">${file.description}</span>
                        </div>
                        ` : ''}
                    </div>
                    <div class="file-actions">
                        ${isLink ? `
                            <a href="${file.link_url}" target="_blank" class="btn btn-primary">
                                <i class="bi bi-box-arrow-up-right"></i> فتح الرابط
                            </a>
                        ` : `
                            <a href="${file.file_path}" download="${file.file_name}" class="btn btn-primary">
                                <i class="bi bi-download"></i> تحميل
                            </a>
                        `}
                        <button class="btn btn-secondary copy-link" data-url="${isLink ? file.link_url : file.file_path}">
                            <i class="bi bi-clipboard"></i> نسخ الرابط
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        // إضافة النافذة إلى DOM
        $('body').append(detailsContent);
        
        // إضافة أحداث للنافذة
        $('.close-modal').click(function() {
            $('.file-details-modal').remove();
        });
        
        $('.copy-link').click(function() {
            const url = $(this).data('url');
            navigator.clipboard.writeText(url).then(() => {
                alert('تم نسخ الرابط إلى الحافظة');
            }).catch(err => {
                console.error('Failed to copy:', err);
                alert('فشل في نسخ الرابط');
            });
        });
        
        // إغلاق النافذة عند الضغط خارجها
        $(document).mouseup(function(e) {
            const modal = $('.file-details-modal .modal-content');
            if (!modal.is(e.target) && modal.has(e.target).length === 0) {
                $('.file-details-modal').remove();
            }
        });
    }
    
    // دالة لتحويل حجم الملف إلى صيغة مقروءة
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // عرض الملفات في الواجهة
    function renderFiles(files) {
        const container = $('#filesContainer');
        container.empty();
        
        if (files.length === 0) {
            container.html(`
                <div style="
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    height: 300px;
                    text-align: center;
                    color: #6c757d;
                ">
                    <i class="bi bi-folder-x" style="font-size: 4rem; margin-bottom: 1rem;"></i>
                    <h4>لا توجد ملفات أو روابط مرفوعة بعد</h4>
                </div>
            `);
            return;
        }

        files.forEach(file => {
            const isLink = file.link_url !== null;
            const fullName = isLink ? file.link_name : file.file_name;
            // أخذ 30% من طول الاسم
            const nameLength = Math.floor(fullName.length * 0.3);
            const shortName = fullName.length > nameLength ? 
                fullName.substring(0, nameLength) + '...' : 
                fullName;
            const fileType = isLink ? 'link' : file.file_type;
            const iconClass = getFileIcon(fileType, isLink);
            
            const fileElement = `
                <div class="card-file" data-id="${file.id}">
                    <i class="bi ${iconClass}"></i>
                    <h3 title="${fullName}">${shortName}</h3>
                </div>
            `;
            
            container.append(fileElement);
        });
        
        // إضافة حدث النقر على الملفات
        $('.card-file').click(function() {
            const fileId = $(this).data('id');
            getFileDetails(fileId);
        });
    }
    
    // تحميل الملفات عند فتح الصفحة
    loadFiles();
    
    // يمكنك إضافة حدث لتحديث القائمة عند رفع ملف جديد
    $(document).on('fileUploaded', function() {
        loadFiles();
    });
});

// Get Storage.php
$(document).ready(function() {
    // دالة لتحديث مساحة التخزين
    function updateStorageUsage() {
        $.get('../api/get_storage.php', function(response) {
            if (response.success) {
                // تحديث النص
                $('.your-storage p span:first').text(response.used_gb + ' GB');
                $('.your-storage p span:last').text(response.total_gb + ' GB');
                
                // تحديث دائرة التقدم
                const circumference = 565; // 2 * π * 90
                const offset = circumference - (response.percentage / 100 * circumference);
                $('.progress-circle-fill').css('stroke-dashoffset', offset);
                $('.progress-text').text(response.percentage.toFixed(0) + '%');
                
                // تغيير لون الدائرة حسب النسبة
                if (response.percentage > 80) {
                    $('.progress-circle-fill').css('stroke', '#f44336'); // أحمر
                } else if (response.percentage > 60) {
                    $('.progress-circle-fill').css('stroke', '#ff9800'); // برتقالي
                } else {
                    $('.progress-circle-fill').css('stroke', 'url(#gradient)'); // الأزرق الافتراضي
                }
            }
        }, 'json');
    }
    
    // تحديث المساحة عند تحميل الصفحة
    updateStorageUsage();
    
    // يمكنك استدعاء هذه الدالة عند رفع أو حذف ملف
    $(document).on('storageUpdated', function() {
        updateStorageUsage();
    });
});