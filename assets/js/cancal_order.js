// scripts/cancel_order.js
function submitCancelRequest() {
    const cancelReason = document.getElementById('cancelReason').value;
    const id = '<?= $order_data["id"] ?>'; // تغيير من order_id إلى id
    const phoneNumber = '<?= $order_data["phone_number"] ?>';
    
    if (!cancelReason) {
        alert('الرجاء إدخال سبب الإلغاء');
        return;
    }
    
    fetch('../api/cancel_order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            id: id, // تغيير من order_id إلى id
            cancel_reason: cancelReason,
            phone_number: phoneNumber
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // إغلاق النافذة المنبثقة
            const modal = bootstrap.Modal.getInstance(document.getElementById('cancelOrderModal'));
            modal.hide();
            
            // عرض رسالة نجاح
            alert('تم إلغاء الطلب بنجاح');
            
            // إرسال إشعار إلى Telegram
            sendTelegramNotification(orderId, phoneNumber, cancelReason);
            
            // إعادة توجيه المستخدم أو تحديث الصفحة
            window.location.reload();
        } else {
            alert('حدث خطأ أثناء محاولة إلغاء الطلب: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء الاتصال بالخادم');
    });
}

function sendTelegramNotification(orderId, phoneNumber, cancelReason) {
    const telegramBotToken = '8403544536:AAHqqOWipI-PXZ0e3Ndy_H28x2gX50ldOeQ';
    const chatId = '@asorders';
    
    const message = `
        ⚠️ تم إلغاء الطلب
        -----------------
        🆔 رقم الطلب: ${orderId}
        📞 رقم الهاتف: ${phoneNumber}
        📝 سبب الإلغاء: ${cancelReason}
        🕒 وقت الإلغاء: ${new Date().toLocaleString()}
    `;
    
    fetch(`https://api.telegram.org/bot${telegramBotToken}/sendMessage`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            chat_id: chatId,
            text: message,
            parse_mode: 'HTML'
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('تم إرسال إشعار الإلغاء إلى Telegram:', data);
    })
    .catch(error => {
        console.error('خطأ في إرسال إشعار الإلغاء إلى Telegram:', error);
    });
}