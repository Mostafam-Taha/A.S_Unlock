$(document).ready(function() {
    // عرض تفاصيل الطلب
    $('.view-btn').click(function() {
        const id = $(this).data('id');
        $('#detailsContent').load('../api/get_application_details.php?id=' + id);
    });

    // فتح نافذة القبول
    $('.accept-btn').click(function() {
        const id = $(this).data('id');
        const phone = $(this).data('phone');
        const name = $(this).data('name');
        
        $('#applicationId').val(id);
        $('#applicationPhone').val(phone);
        $('#actionType').val('accept');
        
        $('#messageModalTitle').text('رسالة قبول الطلب');
        $('#messageText').val(`مرحباً ${name}،\n\nنود إبلاغك بأن طلبك للوظيفة قد تم قبوله. سنتواصل معك قريباً للتفاصيل.\n\nمع تحياتنا: AS-Unlock`);
        
        $('#messageModal').modal('show');
    });

    // فتح نافذة الرفض
    $('.reject-btn').click(function() {
        const id = $(this).data('id');
        const phone = $(this).data('phone');
        const name = $(this).data('name');
        
        $('#applicationId').val(id);
        $('#applicationPhone').val(phone);
        $('#actionType').val('reject');
        
        $('#messageModalTitle').text('رسالة رفض الطلب');
        $('#messageText').val(`السيد/ة ${name}،\n\nنأسف لإبلاغك بأن طلبك للوظيفة قد تم رفضه. نشكرك على اهتمامك.\n\nمع تحياتنا: AS-Unlock`);
        
        $('#messageModal').modal('show');
    });

    // إرسال الرسالة
    $('#sendMessageBtn').click(function() {
        const id = $('#applicationId').val();
        const phone = $('#applicationPhone').val();
        const action = $('#actionType').val();
        const message = $('#messageText').val();
        
        if (!message.trim()) {
            alert('الرجاء كتابة نص الرسالة');
            return;
        }
        
        $.ajax({
            url: '../api/update_status.php',
            method: 'POST',
            data: { 
                id: id,
                status: action,
                message: message
            },
            success: function(response) {
                if (response.success) {
                    alert('تم تحديث الحالة وإرسال الرسالة بنجاح');
                    $('#messageModal').modal('hide');
                    window.open(`https://wa.me/+20${phone}?text=${encodeURIComponent(message)}`, '_blank');
                    setTimeout(() => { location.reload(); }, 1000);
                } else {
                    alert('حدث خطأ: ' + response.message);
                }
            },
            error: function() {
                alert('حدث خطأ في الاتصال بالخادم');
            }
        });
    });
});
// ===============
// jobs
// ===============
document.getElementById('submitJobBtn').addEventListener('click', async function() {
    const form = document.getElementById('jobForm');
    form.classList.add('was-validated');
    
    // التحقق من الحقول المطلوبة
    const requiredFields = ['jobTitle', 'jobCategory', 'jobDescription'];
    let isValid = true;
    
    requiredFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('is-invalid');
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    if (!isValid) {
        Swal.fire({
            icon: 'error',
            title: 'خطأ',
            text: 'يرجى ملء جميع الحقول المطلوبة'
        });
        return;
    }
    
    const formData = new FormData();
    
    // إضافة الحقول الأساسية
    formData.append('jobTitle', document.getElementById('jobTitle').value.trim());
    formData.append('jobCategory', document.getElementById('jobCategory').value);
    formData.append('jobDescription', document.getElementById('jobDescription').value.trim());
    formData.append('jobPublished', document.getElementById('jobPublished').checked ? '1' : '0');
    
    // إضافة جميع الحقول الاختيارية
    const optionalFields = [
        'jobType', 'jobSalary', 'jobLocation', 'jobCompany', 
        'jobCompanyWebsite', 'jobExperience', 'jobSkills',
        'jobBenefits', 'jobContactEmail', 'jobDeadline'
    ];
    
    optionalFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field && field.value.trim()) {
            formData.append(fieldId, field.value.trim());
        }
    });
    
    // إضافة ملف الصورة إذا تم اختياره
    const imageInput = document.getElementById('jobImage');
    if (imageInput.files.length > 0) {
        formData.append('jobImage', imageInput.files[0]);
    }
    
    // إضافة المرفقات
    const attachmentsInput = document.getElementById('jobAttachments');
    if (attachmentsInput.files.length > 0) {
        for (let i = 0; i < attachmentsInput.files.length; i++) {
            formData.append('jobAttachments[]', attachmentsInput.files[i]);
        }
    }
    
    // إضافة CSRF token
    document.querySelector('meta[name="csrf-token"]').content

    try {
        const response = await fetch('../api/add_job.php', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            $('#addJobModal').modal('hide');
            form.reset();
            // إعادة تعيين حقول الملفات
            imageInput.value = '';
            attachmentsInput.value = '';
            form.classList.remove('was-validated');
            
            // إعادة تحميل الجدول إذا كانت الدالة موجودة
            if (typeof loadJobsTable === 'function') {
                loadJobsTable();
            }
            
            Swal.fire({
                icon: 'success',
                title: 'تمت العملية',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'خطأ',
            text: error.message || 'حدث خطأ أثناء محاولة إضافة الوظيفة'
        });
    }
});

function loadJobsTable() {
    fetch('../api/jobs_table.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            document.querySelector('#jobsTable tbody').innerHTML = data;
            initJobActions();
        })
        .catch(error => {
            console.error('Error loading jobs table:', error);
            document.querySelector('#jobsTable tbody').innerHTML = 
                '<tr><td colspan="6" class="text-danger">حدث خطأ أثناء تحميل البيانات</td></tr>';
        });
}

function addTableEventListeners() {
    document.querySelectorAll('.toggle-publish').forEach(btn => {
        btn.addEventListener('click', function() {
            const jobId = this.getAttribute('data-id');
            const action = this.textContent.trim() === 'نشر' ? 'publish' : 'unpublish';

        fetch('../api/update_job_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
                body: `id=${jobId}&action=${action}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                    loadJobsTable();
            }
            });
        });
});

}

$(document).ready(function() {
    $(document).on('click', '.delete-job', function(e) {
        e.preventDefault();
        
        var jobId = $(this).data('id');
        
        // تأكيد الحذف
        if (confirm('هل أنت متأكد من أنك تريد حذف هذه الوظيفة؟')) {
            $.ajax({
                url: '../api/delete_job.php',
                type: 'POST',
                data: { id: jobId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $(this).closest('tr').remove();
                        alert('تم حذف الوظيفة بنجاح');
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف الوظيفة: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('حدث خطأ في الاتصال بالخادم: ' + error);
                }
            });
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    loadJobsTable();
});

function loadJobsTable() {
    const cachedJobs = localStorage.getItem('jobsCache');
    if (cachedJobs) {
        renderJobsTable(JSON.parse(cachedJobs));
    }

    fetch('../api/get_jobs.php')
        .then(response => response.json())
        .then(data => {
            renderJobsTable(data);
            localStorage.setItem('jobsCache', JSON.stringify(data));
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function renderJobsTable(jobs) {
    const tbody = document.querySelector('#jobsTable tbody');
    tbody.innerHTML = '';

    if (jobs.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center text-muted">
                    لا توجد وظائف متاحة حالياً
                </td>
            </tr>`;
        return;
    }

    jobs.forEach(job => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${job.id}</td>
            <td>${job.title}</td>
            <td>${job.category}</td>
            <td>
                ${job.is_published ? 
                    '<span class="badge bg-success">منشور</span>' : 
                    '<span class="badge bg-secondary">غير منشور</span>'}
            </td>
            <td>${new Date(job.created_at).toLocaleDateString()}</td>
            <td>
                <button class="btn btn-sm ${job.is_published ? 'btn-warning' : 'btn-success'} toggle-publish" 
                        data-id="${job.id}">
                    ${job.is_published ? 'إخفاء' : 'نشر'}
                </button>
                <button class="btn btn-sm btn-danger delete-job" data-id="${job.id}">حذف</button>
            </td>`;
        tbody.appendChild(row);
    });

    addTableEventListeners();
}