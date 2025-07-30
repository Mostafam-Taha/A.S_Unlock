$(document).ready(function () {
    // إضافة حقل ميزة جديد عند النقر على زر +
    $(document).on('click', '.add-feature-btn', function () {
        const newFeature = `
      <div class="input-group mb-2" style="direction: ltr;">
        <input type="text" class="form-control feature-input" name="features[]" required>
        <button type="button" class="btn btn-danger remove-feature-btn">-</button>
      </div>
    `;
        $('#featuresContainer').append(newFeature);
    });

    // إزالة حقل ميزة
    $(document).on('click', '.remove-feature-btn', function () {
        $(this).closest('.input-group').remove();
    });

    // حفظ الخطة
    $('#savePlanBtn').click(function () {
        const form = $('#addPlanForm');
        if (form[0].checkValidity()) {
            const planData = {
                icon: $('#planIcon').val(),
                name: $('#planName').val(),
                price: $('#planPrice').val(),
                discount: $('#planDiscount').val(),
                best_seller: $('#bestSeller').is(':checked') ? 1 : 0,
                features: $('.feature-input').map(function () {
                    return $(this).val();
                }).get()
            };

            $.ajax({
                url: '../api/save_plan.php',
                type: 'POST',
                data: planData,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        $('#addPersonModal').modal('hide');
                        form[0].reset();
                        $('#featuresContainer').html(`
              <div class="input-group mb-2">
                <input type="text" class="form-control feature-input" name="features[]" required>
                <button type="button" class="btn btn-success add-feature-btn">+</button>
              </div>
            `);
                        alert('تم حفظ الخطة بنجاح');
                        // يمكنك هنا تحديث الجدول أو القائمة لعرض الخطة الجديدة
                    } else {
                        alert('حدث خطأ: ' + response.message);
                    }
                },
                error: function (xhr, status, error) {
                    alert('حدث خطأ في الاتصال بالخادم');
                    console.error(error);
                }
            });
        } else {
            form[0].reportValidity();
        }
    });
});
// 
// 
// 
$(document).ready(function() {
    // عند النقر على زر عرض
    $('.view-item').click(function(e) {
        e.preventDefault();
        var planId = $(this).data('id');
        
        // جلب بيانات الخطة من السيرفر
        $.ajax({
            url: '../api/get_plan.php',
            type: 'GET',
            data: {id: planId},
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    // تعبئة النموذج بالبيانات
                    $('#planId').val(response.plan.id);
                    $('#name').val(response.plan.name);
                    $('#price').val(response.plan.price);
                    $('#discount').val(response.plan.discount);
                    $('#best_seller').prop('checked', response.plan.best_seller == 1);
                    $('#status').prop('checked', response.plan.status == 1);
                    
                    // جمع المميزات في نص واحد
                    var featuresText = '';
                    if(response.features && response.features.length > 0) {
                        featuresText = response.features.map(f => f.feature).join('\n');
                    }
                    $('#features').val(featuresText);
                    
                    // عرض النافذة المنبثقة
                    $('#planModal').modal('show');
                } else {
                    alert('حدث خطأ أثناء جلب البيانات: ' + response.message);
                }
            },
            error: function() {
                alert('حدث خطأ أثناء الاتصال بالسيرفر');
            }
        });
    });
    
    // عند النقر على زر الحفظ
    $('#savePlan').click(function() {
        var formData = $('#planForm').serialize();
        
        $.ajax({
            url: '../api/update_plan.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    alert('تم تحديث البيانات بنجاح');
                    $('#planModal').modal('hide');
                    location.reload(); // إعادة تحميل الصفحة لرؤية التغييرات
                } else {
                    alert('حدث خطأ أثناء الحفظ: ' + response.message);
                }
            },
            error: function() {
                alert('حدث خطأ أثناء الاتصال بالسيرفر');
            }
        });
    });
    
    // عند النقر على زر الحذف
    $('.delete-item').click(function(e) {
        e.preventDefault();
        if(confirm('هل أنت متأكد من رغبتك في حذف هذه الخطة؟')) {
            var planId = $(this).data('id');
            
            $.ajax({
                url: '../api/delete_plan.php',
                type: 'POST',
                data: {id: planId},
                dataType: 'json',
                success: function(response) {
                    if(response.success) {
                        alert('تم حذف الخطة بنجاح');
                        location.reload(); // إعادة تحميل الصفحة لرؤية التغييرات
                    } else {
                        alert('حدث خطأ أثناء الحذف: ' + response.message);
                    }
                },
                error: function() {
                    alert('حدث خطأ أثناء الاتصال بالسيرفر');
                }
            });
        }
    });
});

$(document).ready(function(){
    $("#search-table-product").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#itemsTable tbody tr").filter(function() {
            $(this).toggle($(this).find("td:eq(1)").text().toLowerCase().indexOf(value) > -1)
        });
    });
});