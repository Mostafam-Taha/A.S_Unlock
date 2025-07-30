// دالة لإرسال البيانات إلى Telegram
async function sendToTelegram(formData) {
    try {
        // إعداد البيانات للإرسال
        const message = `
        📌 *طلب توظيف جديد* 📌
        
        *الاسم الكامل:* ${formData.fullName}
        *رقم الهاتف:* ${formData.phoneNumber}
        *العنوان:* ${formData.address}
        *نوع العمل:* ${formData.workType === 'online' ? 'عمل عن بعد' : 'عمل في موقع'}
        *المهارات:* ${formData.skills.join('، ')}
        `;

        // إرسال الطلب إلى خادم وسيط (backend) لتجنب عرض التوكن في الكود الأمامي
        const response = await fetch('../includes/send_to_telegram.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ message: message })
        });

        const result = await response.json();
        if (!response.ok) {
            console.error('Error sending to Telegram:', result.error);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// تعديل دالة إرسال النموذج
document.getElementById('jobApplicationForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // جمع بيانات النموذج
    const formData = {
        fullName: document.getElementById('fullName').value,
        phoneNumber: document.getElementById('phoneNumber').value,
        address: document.getElementById('address').value,
        workType: document.querySelector('input[name="workType"]:checked').value,
        skills: Array.from(document.querySelectorAll('.skill-tag')).map(tag => tag.textContent)
    };

    // إرسال البيانات إلى Telegram
    await sendToTelegram(formData);
    
    // متابعة باقي إجراءات الإرسال...
    // ... الكود الحالي لإظهار الملخص ...
});