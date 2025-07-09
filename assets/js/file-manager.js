const uploadBtn = document.getElementById('uploadBtn');
const uploadWindow = document.getElementById('uploadWindow');
const closeBtn = document.getElementById('closeBtn');

// Show window when button is clicked
uploadBtn.addEventListener('click', function () {
    uploadWindow.classList.add('active');
});

// Hide window when close button is clicked
closeBtn.addEventListener('click', function () {
    uploadWindow.classList.remove('active');
});

// Hide window when clicking outside the content
uploadWindow.addEventListener('click', function (e) {
    if (e.target === uploadWindow) {
        uploadWindow.classList.remove('active');
    }
});









const linkBtn = document.getElementById('linkBtn');
const fileBtn = document.getElementById('fileBtn');
const rowOne = document.getElementById('rowOne');
const rowTwo = document.getElementById('rowTwo');

// Initialize with file upload visible
toggleViews(true);

// Link button click handler
linkBtn.addEventListener('click', function () {
    if (!this.classList.contains('active')) {
        toggleViews(false);
    }
});

// File button click handler
fileBtn.addEventListener('click', function () {
    if (!this.classList.contains('active')) {
        toggleViews(true);
    }
});

function toggleViews(showFiles) {
    if (showFiles) {
        // Show file upload, hide link upload
        rowOne.style.display = 'block';
        rowTwo.style.display = 'none';

        // Update button states
        fileBtn.classList.add('active');
        linkBtn.classList.remove('active');
    } else {
        // Show link upload, hide file upload
        rowOne.style.display = 'none';
        rowTwo.style.display = 'block';

        // Update button states
        linkBtn.classList.add('active');
        fileBtn.classList.remove('active');
    }
}
// 
// 
// 
$(document).ready(function () {
    // عناصر الواجهة
    const uploadWindow = $('#uploadWindow');
    const closeBtn = $('#closeBtn');
    const linkBtn = $('#linkBtn');
    const fileBtn = $('#fileBtn');
    const rowOne = $('#rowOne');
    const rowTwo = $('#rowTwo');
    const fileInput = $('#upload-file');
    const uploadArea = $('.input-upload');
    const doneBtn = $('.done');
    const finishBtn = $('.true-upload-lok');

    // متغيرات التحميل
    let filesToUpload = [];
    let activeUploads = {};
    const MAX_SIZE = 500 * 1024 * 1024; // 500MB

    // تبديل بين رفع الملفات والروابط
    linkBtn.click(function () {
        $(this).addClass('active');
        fileBtn.removeClass('active');
        rowOne.hide();
        rowTwo.show();
    });

    fileBtn.click(function () {
        $(this).addClass('active');
        linkBtn.removeClass('active');
        rowTwo.hide();
        rowOne.show();
    });

    // إغلاق نافذة الرفع
    closeBtn.click(function () {
        uploadWindow.hide();
    });

    // منع السلوك الافتراضي لسحب الملفات
    $(document).on('dragenter dragover drop', function (e) {
        e.preventDefault();
    });

    // إضافة تأثير عند سحب الملفات
    uploadArea.on('dragenter dragover', function () {
        $(this).addClass('dragover');
    }).on('dragleave drop', function () {
        $(this).removeClass('dragover');
    });

    // معالجة سحب وإسقاط الملفات
    uploadArea.on('drop', function (e) {
        e.preventDefault();
        handleFiles(e.originalEvent.dataTransfer.files);
    });

    // معالجة اختيار الملفات عبر الزر
    fileInput.change(function () {
        handleFiles(this.files);
    });

    // معالجة الملفات المختارة
    function handleFiles(files) {
        let totalSize = 0;

        // حساب الحجم الكلي للملفات
        for (let i = 0; i < files.length; i++) {
            totalSize += files[i].size;
        }

        // التحقق من عدم تجاوز الحد الأقصى
        if (totalSize > MAX_SIZE) {
            alert('إجمالي حجم الملفات يتجاوز 500 ميجابايت');
            return;
        }

        // إضافة الملفات إلى قائمة الانتظار
        for (let i = 0; i < files.length; i++) {
            addFileToQueue(files[i]);
        }

        // بدء التحميل التلقائي
        startUploads();
    }

    // إضافة ملف إلى قائمة الانتظار وعرضه في الواجهة
    function addFileToQueue(file) {
        const fileId = 'file-' + Date.now() + '-' + Math.floor(Math.random() * 1000);
        filesToUpload.push({
            id: fileId,
            file: file,
            status: 'queued'
        });

        const fileElement = `
            <div class="fill-rule-up" id="${fileId}">
                <i class="bi bi-file-earmark-medical-fill"></i>
                <div class="file-rule">
                    <div class="rule-detiles">
                        <span class="name">${file.name}</span>
                        <div class="rule">
                            <span class="pause-btn"><i class="bi bi-pause-circle"></i></span>
                            <span class="cancel-btn"><i class="bi bi-x-circle"></i></span>
                            <div>
                                <p><span class="uploaded-size">0 MB</span> من <span class="total-size">${formatFileSize(file.size)}</span></p> 
                                <span class="num-rule">0%</span>
                            </div>
                        </div>
                    </div>
                    <div class="rule-upload">
                        <div class="index-rule-fon" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        `;

        $('.fill-rule-up').last().after(fileElement);

        // إضافة معالجات الأحداث للأزرار
        $(`#${fileId} .pause-btn`).click(function () {
            togglePause(fileId);
        });

        $(`#${fileId} .cancel-btn`).click(function () {
            cancelUpload(fileId);
        });
    }

    // بدء تحميل الملفات
    function startUploads() {
        filesToUpload.forEach(fileInfo => {
            if (fileInfo.status === 'queued') {
                uploadFile(fileInfo);
            }
        });
    }

    // تحميل الملف
    function uploadFile(fileInfo) {
        const fileId = fileInfo.id;
        const file = fileInfo.file;
        const formData = new FormData();

        formData.append('file', file);
        formData.append('fileId', fileId);
        formData.append('action', 'upload');

        filesToUpload.find(f => f.id === fileId).status = 'uploading';
        activeUploads[fileId] = {
            xhr: new XMLHttpRequest(),
            progress: 0
        };

        const xhr = activeUploads[fileId].xhr;

        xhr.upload.addEventListener('progress', function (e) {
            if (e.lengthComputable) {
                const percent = Math.round((e.loaded / e.total) * 100);
                updateProgress(fileId, percent, e.loaded, e.total);
            }
        });

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        filesToUpload.find(f => f.id === fileId).status = 'completed';
                        $(`#${fileId} .pause-btn`).hide();
                    } else {
                        filesToUpload.find(f => f.id === fileId).status = 'failed';
                        $(`#${fileId}`).addClass('upload-failed');
                    }
                } else {
                    filesToUpload.find(f => f.id === fileId).status = 'failed';
                    $(`#${fileId}`).addClass('upload-failed');
                }

                delete activeUploads[fileId];
                checkAllUploadsComplete();
            }
        };

        xhr.open('POST', '../api/upload.php', true);
        xhr.send(formData);
    }

    // تحديث شريط التقدم
    function updateProgress(fileId, percent, uploaded, total) {
        $(`#${fileId} .index-rule-fon`).css('width', percent + '%');
        $(`#${fileId} .num-rule`).text(percent + '%');
        $(`#${fileId} .uploaded-size`).text(formatFileSize(uploaded));
        activeUploads[fileId].progress = percent;
    }

    // تبديل حالة الإيقاف المؤقت
    function togglePause(fileId) {
        const fileInfo = filesToUpload.find(f => f.id === fileId);

        if (!fileInfo) return;

        if (fileInfo.status === 'uploading') {
            activeUploads[fileId].xhr.abort();
            fileInfo.status = 'paused';
            $(`#${fileId} .pause-btn i`).removeClass('bi-pause-circle').addClass('bi-play-circle');
        } else if (fileInfo.status === 'paused') {
            fileInfo.status = 'queued';
            $(`#${fileId} .pause-btn i`).removeClass('bi-play-circle').addClass('bi-pause-circle');
            uploadFile(fileInfo);
        }
    }

    // إلغاء التحميل
    function cancelUpload(fileId) {
        const fileInfo = filesToUpload.find(f => f.id === fileId);

        if (!fileInfo) return;

        if (fileInfo.status === 'uploading') {
            activeUploads[fileId].xhr.abort();
        }

        filesToUpload = filesToUpload.filter(f => f.id !== fileId);
        $(`#${fileId}`).remove();

        // إرسال طلب لحذف الملف من الخادم إذا تم تحميل جزء منه
        if (activeUploads[fileId] && activeUploads[fileId].progress > 0) {
            $.post('../api/upload.php', {
                action: 'cancel',
                fileId: fileId
            });
        }

        delete activeUploads[fileId];
    }

    // التحقق من اكتمال جميع التحميلات
    function checkAllUploadsComplete() {
        const allComplete = filesToUpload.every(f => f.status === 'completed' || f.status === 'failed');
        if (allComplete) {
            finishBtn.prop('disabled', false);
        }
    }

    // تنسيق حجم الملف
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // زر التأكيد للروابط
    finishBtn.click(function () {
        // إذا كانت نافذة الروابط ظاهرة
        if (rowTwo.is(':visible')) {
            const linkUrl = $('#link').val();
            if (!linkUrl) {
                alert('الرجاء إدخال الرابط أولاً');
                return;
            }

            // نفس كود حفظ الرابط السابق
            const linkName = $('#name-link').val();
            const linkSize = $('#name-size').val();
            const linkDescription = $('#dis-link').val();

            $.post('../api/upload.php', {
                action: 'save_link',
                link_url: linkUrl,
                link_name: linkName,
                link_size: linkSize,
                link_description: linkDescription
            }, function (response) {
                if (response.success) {
                    alert('تم حفظ الرابط بنجاح');
                    resetLinkForm();
                    uploadWindow.hide();
                } else {
                    alert('حدث خطأ أثناء حفظ الرابط: ' + response.message);
                }
            }, 'json');
        }
        // إذا كانت نافذة الملفات ظاهرة
        else {
            if (filesToUpload.length === 0) {
                alert('لم يتم رفع أي ملفات');
                return;
            }

            const fileIds = filesToUpload.map(f => f.id);

            $.post('../api/upload.php', {
                action: 'save_files',
                fileIds: fileIds
            }, function (response) {
                if (response.success) {
                    alert('تم حفظ جميع الملفات بنجاح');
                    resetUploadForm();
                    uploadWindow.hide();
                } else {
                    alert('حدث خطأ أثناء حفظ الملفات: ' + response.message);
                }
            }, 'json');
        }
    });

    // إعادة تعيين نموذج الروابط
    function resetLinkForm() {
        $('#link').val('');
        $('#name-link').val('');
        $('#name-size').val('');
        $('#dis-link').val('');
    }

    // إعادة تعيين نموذج التحميل
    function resetUploadForm() {
        filesToUpload = [];
        activeUploads = {};
        $('.fill-rule-up').not(':first').remove();
        fileInput.val('');
    }
});


function updateUIStatus() {
    // تحديث قسم الملفات
    const filesSection = $('#filesSection');
    if (filesToUpload.length > 0) {
        filesSection.addClass('has-items');
        $('#filesCount').remove();
        filesSection.find('h1').append('<span id="filesCount" class="item-count">' + filesToUpload.length + '</span>');
    } else {
        filesSection.removeClass('has-items');
        $('#filesCount').remove();
    }

    // تحديث قسم الروابط
    const linksSection = $('#linksSection');
    const linkUrl = $('#link').val();
    if (linkUrl) {
        linksSection.addClass('has-items');
    } else {
        linksSection.removeClass('has-items');
    }
}