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
        
        // تنسيق حجم الملف
        const fileSize = isLink ? 
            (file.link_size ? file.link_size + ' MB' : 'غير محدد') : 
            formatFileSize(file.file_size);
        
        // إنشاء محتوى نافذة التفاصيل مع إضافة خيارات التعديل والحذف
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
                        ${file.description || file.link_description ? `
                        <div class="info-row">
                            <span class="info-label">الوصف:</span>
                            <span class="info-value">${isLink ? file.link_description : file.description}</span>
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
                        <button class="btn btn-warning edit-file" data-id="${file.id}">
                            <i class="bi bi-pencil"></i> تعديل
                        </button>
                        <button class="btn btn-danger delete-file" data-id="${file.id}" data-path="${isLink ? '' : file.file_path}">
                            <i class="bi bi-trash"></i> حذف
                        </button>
                    </div>
                    
                    <!-- نموذج التعديل (مخفي بشكل افتراضي) -->
                    <div class="edit-form" style="display: none; margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                        <h4>تعديل ${isLink ? 'الرابط' : 'الملف'}</h4>
                        <div class="form-group">
                            <label>اسم ${isLink ? 'الرابط' : 'الملف'}</label>
                            <input type="text" class="form-control name-input" value="${isLink ? file.link_name : file.file_name}">
                        </div>
                        <div class="form-group">
                            <label>الوصف</label>
                            <textarea class="form-control description-input">${isLink ? (file.link_description || '') : (file.description || '')}</textarea>
                        </div>
                        ${isLink ? `
                        <div class="form-group">
                            <label>رابط URL</label>
                            <input type="text" class="form-control url-input" value="${file.link_url}">
                        </div>
                        <div class="form-group">
                            <label>حجم الرابط (MB)</label>
                            <input type="number" class="form-control size-input" value="${file.link_size || ''}">
                        </div>
                        ` : ''}
                        <div class="form-actions" style="margin-top: 15px;">
                            <button class="btn btn-success save-changes" data-id="${file.id}">
                                <i class="bi bi-check"></i> حفظ التغييرات
                            </button>
                            <button class="btn btn-secondary cancel-edit">
                                <i class="bi bi-x"></i> إلغاء
                            </button>
                        </div>
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
        
        // حدث زر التعديل
        $('.edit-file').click(function() {
            $(this).closest('.modal-content').find('.edit-form').show();
            $(this).hide();
        });
        
        // حدث إلغاء التعديل
        $('.cancel-edit').click(function() {
            $(this).closest('.modal-content').find('.edit-form').hide();
            $(this).closest('.modal-content').find('.edit-file').show();
        });
        
        // حدث حفظ التغييرات
        $('.save-changes').click(function() {
            const fileId = $(this).data('id');
            const modalContent = $(this).closest('.modal-content');
            const isLink = modalContent.find('.url-input').length > 0;
            
            const data = {
                id: fileId,
                name: modalContent.find('.name-input').val(),
                description: modalContent.find('.description-input').val()
            };
            
            if (isLink) {
                data.link_url = modalContent.find('.url-input').val();
                data.link_size = modalContent.find('.size-input').val();
            }
            
            $.post('../api/update_file.php', data, function(response) {
                if (response.success) {
                    alert('تم تحديث البيانات بنجاح');
                    $('.file-details-modal').remove();
                    loadFiles(); // إعادة تحميل القائمة
                } else {
                    alert('فشل في تحديث البيانات: ' + response.message);
                }
            }, 'json');
        });
        
        // حدث زر الحذف
        $('.delete-file').click(function() {
            if (!confirm('هل أنت متأكد من حذف ' + (isLink ? 'الرابط' : 'الملف') + '؟ لن يمكنك استرجاعه بعد الحذف!')) {
                return;
            }
            
            const fileId = $(this).data('id');
            const filePath = $(this).data('path');
            
            $.post('../api/delete_file.php', {id: fileId, path: filePath}, function(response) {
                if (response.success) {
                    alert('تم الحذف بنجاح');
                    $('.file-details-modal').remove();
                    loadFiles(); // إعادة تحميل القائمة
                } else {
                    alert('فشل في الحذف: ' + response.message);
                }
            }, 'json');
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