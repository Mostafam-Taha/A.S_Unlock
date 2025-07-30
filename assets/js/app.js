document.addEventListener('DOMContentLoaded', function() {
    // إدارة المهارات
    const skillsInput = document.getElementById('skills');
    const skillsTagsContainer = document.getElementById('skillsTags');
    
    // إضافة مهارة عند الضغط على Enter أو الفاصلة
    skillsInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            const skill = this.value.trim();
            if (skill) {
                addSkillTag(skill);
                this.value = '';
            }
        }
    });
    
    // دالة لإضافة علامة مهارة
    function addSkillTag(skill) {
        const tag = document.createElement('span');
        tag.className = 'skill-tag';
        tag.innerHTML = `${skill} <span class="remove-tag" onclick="this.parentElement.remove()">×</span>`;
        skillsTagsContainer.appendChild(tag);
    }
    
    // إدارة رفع الصورة
    const uploadBtn = document.getElementById('uploadBtn');
    const profileImageInput = document.getElementById('profileImage');
    const imagePreview = document.getElementById('imagePreview');
    
    uploadBtn.addEventListener('click', function() {
        profileImageInput.click();
    });
    
    profileImageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
                uploadBtn.style.display = 'none';
            }
            
            reader.readAsDataURL(this.files[0]);
        }
    });
    
    // إرسال النموذج
    const jobApplicationForm = document.getElementById('jobApplicationForm');
    
    jobApplicationForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // التحقق من صحة النموذج
        if (!validateForm()) {
            return;
        }
        
        // جمع البيانات
        const formData = new FormData();
        formData.append('fullName', document.getElementById('fullName').value.trim());
        formData.append('phoneNumber', document.getElementById('phoneNumber').value.trim());
        formData.append('address', document.getElementById('address').value.trim());
        formData.append('workType', document.querySelector('input[name="workType"]:checked').value);
        
        // جمع المهارات
        const skills = [];
        document.querySelectorAll('.skill-tag').forEach(tag => {
            skills.push(tag.textContent.replace('×', '').trim());
        });
        formData.append('skills', JSON.stringify(skills));
        
        // إضافة صورة الملف إذا تم تحميلها
        if (profileImageInput.files[0]) {
            formData.append('profileImage', profileImageInput.files[0]);
        }
        
        // إرسال البيانات
        try {
            const response = await fetch('../api/submit_application.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                showSuccessMessage(result.message);
                showApplicationSummary(result.applicationId);
                jobApplicationForm.reset();
                resetFormUI();
            } else {
                showErrorMessage(result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            showErrorMessage('حدث خطأ أثناء إرسال الطلب. يرجى المحاولة مرة أخرى.');
        }
    });
    
    // دالة للتحقق من صحة النموذج
    function validateForm() {
        const fullName = document.getElementById('fullName').value.trim();
        const phoneNumber = document.getElementById('phoneNumber').value.trim();
        const address = document.getElementById('address').value.trim();
        const skills = document.querySelectorAll('.skill-tag');
        
        if (!fullName) {
            showErrorMessage('الرجاء إدخال الاسم الكامل');
            return false;
        }
        
        if (!phoneNumber) {
            showErrorMessage('الرجاء إدخال رقم الهاتف');
            return false;
        }
        
        if (!address) {
            showErrorMessage('الرجاء إدخال العنوان');
            return false;
        }
        
        if (skills.length === 0) {
            showErrorMessage('الرجاء إضافة مهارة واحدة على الأقل');
            return false;
        }
        
        return true;
    }
    
    // دالة لعرض رسالة نجاح
    function showSuccessMessage(message) {
        alert(message); // يمكنك استبدال هذا بتنفيذ أكثر تطوراً
    }
    
    // دالة لعرض رسالة خطأ
    function showErrorMessage(message) {
        alert(message); // يمكنك استبدال هذا بتنفيذ أكثر تطوراً
    }
    
    // دالة لعرض ملخص الطلب
    function showApplicationSummary(applicationId) {
        const fullName = document.getElementById('fullName').value.trim();
        const phoneNumber = document.getElementById('phoneNumber').value.trim();
        const address = document.getElementById('address').value.trim();
        const workType = document.querySelector('input[name="workType"]:checked').value === 'online' ? 'عمل عن بعد' : 'عمل في موقع';
        
        const skills = [];
        document.querySelectorAll('.skill-tag').forEach(tag => {
            skills.push(tag.textContent.replace('×', '').trim());
        });
        
        document.getElementById('summaryContent').innerHTML = `
            <div class="summary-details">
                <div class="summary-item">
                    <span class="summary-label">رقم الطلب:</span>
                    <span class="summary-value">${applicationId}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">الاسم الكامل:</span>
                    <span class="summary-value">${fullName}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">رقم الهاتف:</span>
                    <span class="summary-value">${phoneNumber}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">العنوان:</span>
                    <span class="summary-value">${address}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">نوع العمل:</span>
                    <span class="summary-value">${workType}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">المهارات:</span>
                    <span class="summary-value">${skills.join(', ')}</span>
                </div>
            </div>
        `;
        
        document.getElementById('summaryModal').style.display = 'flex';
    }
    
    // دالة لإعادة تعيين واجهة النموذج
    function resetFormUI() {
        skillsTagsContainer.innerHTML = '';
        imagePreview.src = '';
        imagePreview.style.display = 'none';
        uploadBtn.style.display = 'block';
    }
    
    // إغلاق نافذة الملخص
    document.getElementById('closeSummaryModal').addEventListener('click', function() {
        document.getElementById('summaryModal').style.display = 'none';
    });
    
    document.getElementById('closeSummaryBtn').addEventListener('click', function() {
        document.getElementById('summaryModal').style.display = 'none';
    });
    
    // طباعة الملخص
    document.getElementById('printSummary').addEventListener('click', function() {
        window.print();
    });
}); 












