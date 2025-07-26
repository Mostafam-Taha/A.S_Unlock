$(document).ready(function() {
    // عند النقر على زر الحصول على الخطة
    $('.get-plan-btn').click(function(e) {
        e.preventDefault();
        
        const planId = $(this).data('plan-id');
        const planName = $(this).data('plan-name');
        const planPrice = $(this).data('plan-price');
        
        // تخزين بيانات الخطة في النافذة
        $('#selectedPlanName').text(planName);
        $('#selectedPlanPrice').text(planPrice);
        $('#planId').val(planId);
        
        // التحقق من تسجيل الدخول عبر AJAX
        $.ajax({
            url: '../api/check_login.php',
            type: 'GET',
            success: function(response) {
                if(response.loggedIn) {
                    $('#loginAlert').addClass('d-none');
                    $('#paymentForm').removeClass('d-none');
                    
                    // تعبئة بيانات المستخدم إذا كان مسجلاً
                    $('input[name="email"]').val(response.user.email);
                    $('input[name="phone_number"]').val(response.user.phone || '');
                    
                    // عرض نافذة الدفع
                    $('#paymentModal').modal('show');
                } else {
                    $('#loginAlert').removeClass('d-none');
                    $('#paymentForm').addClass('d-none');
                    $('#paymentModal').modal('show');
                }
            }
        });
    });
    
    // عند تقديم نموذج الدفع
    $('#orderForm').submit(function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '../api/process_payment.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.success) {
                    alert('تم استلام طلبك بنجاح وسيتم مراجعته قريباً');
                    $('#paymentModal').modal('hide');
                } else {
                    alert('حدث خطأ: ' + response.message);
                }
            },
            error: function() {
                alert('حدث خطأ أثناء معالجة الطلب');
            }
        });
    });
});