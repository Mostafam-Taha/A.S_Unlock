document.querySelectorAll('.whatsapp-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const phone = this.getAttribute('data-phone');
        const orderData = JSON.parse(this.getAttribute('data-order'));
        
        // إنشاء محتوى النافذة المنبثقة
        const popupContent = `
            <div class="whatsapp-popup">
                <h3>إرسال رسالة واتساب</h3>
                <div class="order-details">
                    <p><strong>رقم الطلب:</strong> #${orderData.order_id}</p>
                    <p><strong>الاسم:</strong> ${orderData.user_name}</p>
                    <p><strong>نوع الخدمة:</strong> ${orderData.service_type}</p>
                    <p><strong>مدة الضمان :</strong> ${orderData.warranty_duration}</p>
                    <p><strong>رقم الهاتف:</strong> ${orderData.contact_number}</p>
                </div>
                <textarea id="whatsapp-message" rows="5">${generateWhatsAppMessage(orderData)}</textarea>
                <div class="popup-buttons">
                    <button id="send-whatsapp">إرسال عبر واتساب</button>
                    <button id="cancel-whatsapp">إلغاء</button>
                </div>
            </div>
        `;
        
        // عرض النافذة المنبثقة
        const popup = document.createElement('div');
        popup.className = 'popup-overlay';
        popup.innerHTML = popupContent;
        document.body.appendChild(popup);
        
        // معالجة الإرسال
        document.getElementById('send-whatsapp').addEventListener('click', function() {
            const message = document.getElementById('whatsapp-message').value;
            const encodedMessage = encodeURIComponent(message);
            window.open(`https://wa.me/+20${phone}?text=${encodedMessage}`, '_blank');
            document.body.removeChild(popup);
        });
        
        // معالجة الإلغاء
        document.getElementById('cancel-whatsapp').addEventListener('click', function() {
            document.body.removeChild(popup);
        });
    });
});

function generateWhatsAppMessage(orderData) {
    return `الضمان اتفعل وأي حاجة تواجهك كلمني على طول وأنا تحت أمرك 💙👀✨
الضمان ساري لمدة ${orderData.warranty_duration} يوم من ${orderData.warranty_expiry_date}، علشان تكون متطمن 🔒✅.

مع تحيات Ahmed Abd Elshafy

(رقم المراجعة #${orderData.order_id})`;
}