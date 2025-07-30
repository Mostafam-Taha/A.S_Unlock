$(document).ready(function() {
  // إضافة مهارة جديدة
  $(document).on('click', '.add-skill-btn', function() {
    const newSkillInput = `
      <div class="input-group mb-2">
        <input type="text" class="form-control skill-input" name="skills[]" placeholder="أدخل المهارة">
        <button type="button" class="btn btn-outline-danger remove-skill-btn">
          <i class="bi bi-trash"></i>
        </button>
      </div>
    `;
    $('#skillsContainer').append(newSkillInput);
  });
  
  // إزالة مهارة
  $(document).on('click', '.remove-skill-btn', function() {
    $(this).closest('.input-group').remove();
  });
  
  // إضافة رابط تواصل جديد
  $('#addSocialLinkBtn').click(function() {
    const newSocialLink = `
      <div class="row mb-2">
        <div class="col-md-6">
          <input type="text" class="form-control" name="socialIcons[]" placeholder="أيقونة (مثال: bi bi-facebook)">
        </div>
        <div class="col-md-6">
          <input type="url" class="form-control" name="socialLinks[]" placeholder="رابط وسيلة التواصل">
        </div>
      </div>
    `;
    $('#socialLinksContainer').append(newSocialLink);
  });
  
  // حفظ البيانات
  $('#savePersonBtn').click(function() {
    const formData = new FormData($('#addPersonForm')[0]);
    const $btn = $(this);
    
    // تعطيل الزر أثناء المعالجة
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> جاري الحفظ...');
    
    $.ajax({
        url: '../api/save_person.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json', // نتوقع استجابة JSON
        success: function(response) {
            if (response.success) {
                alert('تم الحفظ بنجاح');
                $('#addPersonModal').modal('hide');
                resetForm();
            } else {
                showError(response.message || 'حدث خطأ غير معروف');
            }
        },
        error: function(xhr) {
            let errorMsg = 'حدث خطأ في الاتصال بالخادم';
            try {
                const response = JSON.parse(xhr.responseText);
                if (response && response.message) {
                    errorMsg = response.message;
                }
            } catch (e) {
                errorMsg = xhr.statusText || errorMsg;
            }
            showError(errorMsg);
        },
        complete: function() {
            $btn.prop('disabled', false).text('حفظ');
        }
    });
  });

  function resetForm() {
      $('#addPersonForm')[0].reset();
      $('#skillsContainer').html(`
          <div class="input-group mb-2">
              <input type="text" class="form-control skill-input" name="skills[]" placeholder="أدخل المهارة">
              <button type="button" class="btn btn-outline-primary add-skill-btn">
                  <i class="bi bi-plus-lg"></i>
              </button>
          </div>
      `);
      $('#socialLinksContainer').html(`
          <div class="row mb-2">
              <div class="col-md-6">
                  <input type="text" class="form-control" name="socialIcons[]" placeholder="أيقونة (مثال: bi bi-facebook)">
              </div>
              <div class="col-md-6">
                  <input type="url" class="form-control" name="socialLinks[]" placeholder="رابط وسيلة التواصل">
              </div>
          </div>
      `);
  }

  function showError(message) {
      // يمكنك استبدال هذا بعرض رسالة الخطأ بشكل أفضل
      const $alert = $(`
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
              ${message}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
      `);
      $('#addPersonModal .modal-body').prepend($alert);
      setTimeout(() => $alert.alert('close'), 5000);
  }
});

// ===============
// Get Data& Edit& Delete
// ===============
// عند النقر على رابط "عرض"
$(document).on('click', '.view-item', function(e) {
    e.preventDefault();
    const personId = $(this).data('id');
    
    // إضافة الـ ID إلى URL بدون تحديث الصفحة
    window.history.pushState({}, '', `?view_person=${personId}`);
    // جلب بيانات المستخدم من الخادم
    $.ajax({
        url: '../api/get_person_details.php',
        method: 'POST',
        data: { id: personId },
        dataType: 'json',
        success: function(response) {
            if(response.success) {
                // تعبئة النموذج بالبيانات
                $('#editPersonId').val(response.person.id);
                $('#editPersonType').val(response.person.type);
                $('#editPersonName').val(response.person.name);
                $('#editPersonJob').val(response.person.job);
                $('#editPersonDescription').val(response.person.description);
                
                // عرض الصورة إذا كانت موجودة
                if(response.person.image_path) {
                    // تأكد من أن المسار يحتوي على المجلد الأساسي إذا كان مسارًا نسبيًا
                    $('#editUserImagePreview').attr('src', '../uploads/' + response.person.image_path);
                    
                    // أو يمكنك استخدام مسار مطلق إذا كان متاحًا
                    // $('#editUserImagePreview').attr('src', 'https://موقعك.com/' + response.person.image_path);
                }
                
                // تعبئة المهارات
                const skillsContainer = $('#editSkillsContainer');
                skillsContainer.empty();
                
                if(response.skills && response.skills.length > 0) {
                    response.skills.forEach((skill, index) => {
                        const skillHtml = `
                            <div class="input-group mb-2 skill-row" data-index="${index}">
                                <input type="text" class="form-control edit-skill-input" name="editSkills[]" 
                                       value="${skill.skill}" placeholder="أدخل المهارة">
                                <button type="button" class="btn btn-outline-danger remove-skill-btn">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        `;
                        skillsContainer.append(skillHtml);
                    });
                } else {
                    skillsContainer.append(`
                        <div class="input-group mb-2 skill-row" data-index="0">
                            <input type="text" class="form-control edit-skill-input" name="editSkills[]" placeholder="أدخل المهارة">
                            <button type="button" class="btn btn-outline-danger remove-skill-btn">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    `);
                }
                
                // تعبئة وسائل التواصل
                const socialContainer = $('#editSocialLinksContainer');
                socialContainer.empty();
                
                if(response.socialLinks && response.socialLinks.length > 0) {
                    response.socialLinks.forEach((link, index) => {
                        const socialHtml = `
                            <div class="row mb-2 social-row" data-index="${index}">
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="editSocialIcons[]" 
                                           value="${link.icon_class}" placeholder="أيقونة (مثال: bi bi-facebook)">
                                </div>
                                <div class="col-md-5">
                                    <input type="url" class="form-control" name="editSocialLinks[]" 
                                           value="${link.link}" placeholder="رابط وسيلة التواصل">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-outline-danger w-100 remove-social-btn">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                        socialContainer.append(socialHtml);
                    });
                } else {
                    socialContainer.append(`
                        <div class="row mb-2 social-row" data-index="0">
                            <div class="col-md-5">
                                <input type="text" class="form-control" name="editSocialIcons[]" placeholder="أيقونة (مثال: bi bi-facebook)">
                            </div>
                            <div class="col-md-5">
                                <input type="url" class="form-control" name="editSocialLinks[]" placeholder="رابط وسيلة التواصل">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-outline-danger w-100 remove-social-btn">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    `);
                }
                
                // عرض النافذة
                $('#personDetailsModal').modal('show');
            } else {
                alert('حدث خطأ أثناء جلب بيانات المستخدم');
            }
        },
        error: function() {
            alert('حدث خطأ أثناء الاتصال بالخادم');
        }
    });
});

// إضافة مهارة جديدة
$('#editAddSkillBtn').click(function() {
    const container = $('#editSkillsContainer');
    const index = container.children().length;
    
    const skillHtml = `
        <div class="input-group mb-2 skill-row" data-index="${index}">
            <input type="text" class="form-control edit-skill-input" name="editSkills[]" placeholder="أدخل المهارة">
            <button type="button" class="btn btn-outline-danger remove-skill-btn">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;
    container.append(skillHtml);
});

// إضافة رابط تواصل جديد
$('#editAddSocialLinkBtn').click(function() {
    const container = $('#editSocialLinksContainer');
    const index = container.children().length;
    
    const socialHtml = `
        <div class="row mb-2 social-row" data-index="${index}">
            <div class="col-md-5">
                <input type="text" class="form-control" name="editSocialIcons[]" placeholder="أيقونة (مثال: bi bi-facebook)">
            </div>
            <div class="col-md-5">
                <input type="url" class="form-control" name="editSocialLinks[]" placeholder="رابط وسيلة التواصل">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-outline-danger w-100 remove-social-btn">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.append(socialHtml);
});

// حذف مهارة
$(document).on('click', '.remove-skill-btn', function() {
    if($('#editSkillsContainer .skill-row').length > 1) {
        $(this).closest('.skill-row').remove();
        // إعادة ترقيم العناصر
        $('#editSkillsContainer .skill-row').each(function(index) {
            $(this).attr('data-index', index);
        });
    } else {
        alert('يجب أن يحتوي المستخدم على مهارة واحدة على الأقل');
    }
});

// حذف رابط تواصل
$(document).on('click', '.remove-social-btn', function() {
    if($('#editSocialLinksContainer .social-row').length > 1) {
        $(this).closest('.social-row').remove();
        // إعادة ترقيم العناصر
        $('#editSocialLinksContainer .social-row').each(function(index) {
            $(this).attr('data-index', index);
        });
    } else {
        alert('يجب أن يحتوي المستخدم على رابط تواصل واحد على الأقل');
    }
});

// تحديث بيانات المستخدم
$('#updatePersonBtn').click(function() {
    const formData = new FormData($('#editPersonForm')[0]);
    
    $.ajax({
        url: '../api/update_person.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if(response.success) {
                alert('تم تحديث بيانات المستخدم بنجاح');
                $('#personDetailsModal').modal('hide');
                // إعادة تحميل البيانات أو تحديث الجدول
                // loadPersonsData();
            } else {
                alert('حدث خطأ أثناء تحديث البيانات: ' + response.message);
            }
        },
        error: function() {
            alert('حدث خطأ أثناء الاتصال بالخادم');
        }
    });
});


$(document).ready(function() {
    // معالجة النقر على رابط الحذف
    $(document).on('click', '.delete-item', function(e) {
        e.preventDefault();
        
        var id = $(this).data('id');
        var item = $(this).closest('tr'); // أو أي عنصر يحتوي على العنصر الذي تريد حذفه
        
        if (confirm('هل أنت متأكد أنك تريد حذف هذا العنصر؟')) {
            $.ajax({
                url: '../api/delete_person.php',
                type: 'POST',
                dataType: 'json',
                data: { id: id },
                success: function(response) {
                    if (response.success) {
                        // إزالة العنصر من الواجهة
                        item.fadeOut(300, function() {
                            $(this).remove();
                        });
                        alert('تم الحذف بنجاح');
                    } else {
                        alert('حدث خطأ أثناء الحذف: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('حدث خطأ في الاتصال بالخادم: ' + error);
                }
            });
        }
    });
});