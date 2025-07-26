$(document).ready(function() {
  // تحميل الروابط عند فتح الصفحة
  function loadLinks() {
    $.ajax({
      url: '../api/get_links.php',
      method: 'GET',
      success: function(response) {
        if (response.success) {
          const tbody = $('#itemsTable tbody');
          tbody.empty();
          
          response.data.forEach(link => {
            const row = `
              <tr>
                <td>${link.id}</td>
                <td><a href="${link.url}" target="_blank">${link.name}</a></td>
                <td>${link.is_published ? 'منشور' : 'غير منشور'}</td>
                <td>${new Date(link.created_at).toLocaleDateString('ar-EG')}</td>
                <td>${link.icon}</td>
                <td>
                  <a href="#" class="view-item" data-id="${link.id}">تعديل</a> | 
                  <a href="#" class="delete-item" data-id="${link.id}">حذف</a>
                </td>
              </tr>
            `;
            tbody.append(row);
          });
        } else {
          alert('حدث خطأ: ' + response.message);
        }
      },
      error: function() {
        alert('حدث خطأ أثناء جلب البيانات');
      }
    });
  }

  // فتح نافذة الإضافة عند الضغط على الزر
  $('.btn-plus').click(function() {
    $('#addLinkModal').modal('show');
  });

  // حفظ الرابط الجديد
  $('#saveLinkBtn').click(function() {
      const name = $('#linkName').val();
      const url = $('#linkUrl').val();
      const status = $('#linkStatus').val();
      const selectedIcon = $('#linkIcon').val();
      const customIcon = $('#customIcon').val();

      // تحديد الأيقونة النهائية
      let finalIcon;
      if (customIcon) {
          // إذا كانت أيقونة مخصصة، نستخدمها بعد التحقق من صحتها
          if (!customIcon.startsWith('<i class="') || !customIcon.endsWith('</i>')) {
              alert('يجب أن تكون الأيقونة المخصصة بتنسيق <i class="..."></i> فقط');
              return;
          }
          finalIcon = customIcon;
      } else {
          // إذا لم تكن أيقونة مخصصة، نستخدم القيمة من select مع تغليفها في <i>
          finalIcon = `<i class="${selectedIcon}"></i>`;
      }

      if (!name || !url) {
          alert('الرجاء إدخال اسم اللينك ورابطه');
          return;
      }

      $.ajax({
          url: '../api/save_links.php',
          method: 'POST',
          data: {
              name: name,
              url: url,
              status: status,
              icon: finalIcon
          },
          success: function(response) {
              if (response.success) {
                  $('#addLinkModal').modal('hide');
                  $('#addLinkForm')[0].reset();
                  loadLinks();
              } else {
                  alert('حدث خطأ: ' + response.message);
              }
          },
          error: function() {
              alert('حدث خطأ أثناء الاتصال بالخادم');
          }
      });
  });

  // حذف الرابط
  $(document).on('click', '.delete-item', function(e) {
    e.preventDefault();
    const id = $(this).data('id');
    
    if (confirm('هل أنت متأكد من حذف هذا الرابط؟')) {
      $.ajax({
        url: '../api/delete_link.php',
        method: 'POST',
        data: { id: id },
        success: function(response) {
          if (response.success) {
            loadLinks();
          } else {
            alert('حدث خطأ: ' + response.message);
          }
        }
      });
    }
  });

  // فتح نموذج التعديل
  $(document).on('click', '.view-item', function(e) {
      e.preventDefault();
      const id = $(this).data('id');
      
      $.ajax({
          url: '../api/edit_link.php',
          method: 'GET',
          data: { id: id },
          success: function(response) {
              if (response.success) {
                  const link = response.data;
                  $('#editLinkModal #editId').val(link.id);
                  $('#editLinkModal #editName').val(link.name);
                  $('#editLinkModal #editUrl').val(link.url);
                  $('#editLinkModal #editStatus').val(link.is_published);
                  
                  // التحقق إذا كانت الأيقونة مخصصة
                  if (link.icon.includes('<i class="')) {
                      $('#editLinkModal #editCustomIcon').val(link.icon);
                      $('#editLinkModal #editIcon').val('custom');
                  } else {
                      $('#editLinkModal #editIcon').val(link.icon);
                      $('#editLinkModal #editCustomIcon').val('');
                  }
                  
                  $('#editLinkModal').modal('show');
              } else {
                  alert('حدث خطأ: ' + response.message);
              }
          }
      });
  });

  // حفظ التعديلات
  $('#updateLinkBtn').click(function() {
      const id = $('#editId').val();
      const name = $('#editName').val();
      const url = $('#editUrl').val();
      const status = $('#editStatus').val();
      let icon = $('#editIcon').val();
      const customIcon = $('#editCustomIcon').val();

      if (customIcon) {
          if (!customIcon.startsWith('<i class="') || !customIcon.endsWith('</i>')) {
              alert('يجب أن تكون الأيقونة المخصصة بتنسيق <i class="..."></i> فقط');
              return;
          }
          icon = customIcon;
      }

      if (!name || !url) {
          alert('الرجاء إدخال اسم اللينك ورابطه');
          return;
      }

      $.ajax({
          url: '../api/edit_link.php',
          method: 'POST',
          data: {
              id: id,
              name: name,
              url: url,
              status: status,
              icon: icon
          },
          success: function(response) {
              if (response.success) {
                  $('#editLinkModal').modal('hide');
                  loadLinks();
              } else {
                  alert('حدث خطأ: ' + response.message);
              }
          }
      });
  });

  // البحث الفوري عن الروابط
  $('#search-table-links').on('input', function() {
      const searchTerm = $(this).val().toLowerCase();
        $('table tbody tr').each(function() {
            const rowText = $(this).text().toLowerCase();
            $(this).toggle(rowText.includes(searchTerm));
        });
      if(searchTerm.length > 0) {
          // إذا كان هناك نص للبحث، نستخدم AJAX للبحث على الخادم
          $.ajax({
              url: '../api/search_links.php',
              method: 'GET',
              data: { search: searchTerm },
              success: function(response) {
                  if(response.success) {
                      const tbody = $('#itemsTable tbody');
                      tbody.empty();
                      
                      response.data.forEach(link => {
                          const row = `
                              <tr>
                                  <td>${link.id}</td>
                                  <td><a href="${link.url}" target="_blank">${link.name}</a></td>
                                  <td>${link.is_published ? 'منشور' : 'غير منشور'}</td>
                                  <td>${new Date(link.created_at).toLocaleDateString('ar-EG')}</td>
                                  <td>${link.icon}</td>
                                  <td>
                                      <a href="#" class="view-item" data-id="${link.id}">تعديل</a> | 
                                      <a href="#" class="delete-item" data-id="${link.id}">حذف</a>
                                  </td>
                              </tr>
                          `;
                          tbody.append(row);
                      });
                  } else {
                      alert('حدث خطأ: ' + response.message);
                  }
              }
          });
      } else {
          // إذا كان حقل البحث فارغاً، نعيد تحميل جميع الروابط
          loadLinks();
      }
  });
  // تحميل الروابط عند بدء التشغيل
  loadLinks();
});