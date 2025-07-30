async function submitCancelRequest(orderId) {
    const reason = document.getElementById('cancelReason').value.trim();
    
    if (!reason) {
        Swal.fire({
            icon: 'error',
            title: 'خطأ',
            text: 'يرجى إدخال سبب الإلغاء',
            confirmButtonText: 'حسناً'
        });
        return;
    }
    
    // عرض تحميل
    Swal.fire({
        title: 'جاري معالجة طلبك',
        html: 'الرجاء الانتظار...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    try {
        const response = await fetch('../api/cancel_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                order_id: orderId,
                reason: reason
            })
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'حدث خطأ أثناء معالجة الطلب');
        }
        
        // إغلاق المودال
        const modal = bootstrap.Modal.getInstance(document.getElementById('cancelOrderModal'));
        modal.hide();
        
        // عرض رسالة النجاح
        await Swal.fire({
            icon: 'success',
            title: 'تم بنجاح',
            text: data.message,
            confirmButtonText: 'حسناً'
        });
        
        // إعادة تحميل الصفحة
        window.location.reload();
        
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'خطأ',
            text: error.message || 'حدث خطأ غير متوقع',
            confirmButtonText: 'حسناً'
        });
        console.error('Error:', error);
    }
}