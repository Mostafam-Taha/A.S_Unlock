
function shareFile(fileId) {
    // إنشاء رابط المشاركة
    const shareUrl = `share.php?id=${fileId}`;
    
    // نسخ الرابط إلى الحافظة
    navigator.clipboard.writeText(shareUrl).then(() => {
        alert('تم نسخ رابط المشاركة: ' + shareUrl);
    }).catch(err => {
        // إذا فشل النسخ، عرض الرابط في تنبيه
        alert('رابط المشاركة: ' + shareUrl + '\n\nيمكنك نسخه يدويًا');
    });
    
    // يمكنك أيضًا فتح نافذة مشاركة إذا كان المتصفح يدعم ذلك
    if (navigator.share) {
        navigator.share({
            title: 'مشاركة ملف',
            text: 'تحقق من هذا الملف',
            url: shareUrl
        }).catch(console.error);
    }
}





























document.addEventListener('DOMContentLoaded', function() {
    const section = document.querySelector('.zip-files-size');
    
    // تحديد جميع العناصر التي نريد إضافة التأثير لها
    const elementsToAnimate = [
        section.querySelector('h1'),
        section.querySelector('.img-file'),
        section.querySelector('.link-download h1'),
        section.querySelector('.link-down')
    ].filter(el => el !== null); // تأكد من وجود العناصر
    
    // إضافة كلاس fade-in-element لكل عنصر
    elementsToAnimate.forEach(el => el.classList.add('fade-in-element'));
    
    // إنشاء Intersection Observer
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // عند ظهور القسم في الشاشة، نضيف التأثير لكل عنصر بالتتابع
                elementsToAnimate.forEach((el, index) => {
                    setTimeout(() => {
                        el.classList.add('visible');
                    }, 200 * index);
                });
                observer.unobserve(entry.target);
            }
        });
    }, {threshold: 0.1}); // عندما يكون 10% من القسم ظاهراً
    
    // بدء مراقبة القسم
    observer.observe(section);
});

document.addEventListener('DOMContentLoaded', function() {
    const contactSection = document.querySelector('.contact-us-team');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // عرض رسالة المستخدم أولاً
                const userMessage = entry.target.querySelector('.massage-user');
                userMessage.classList.add('show-message');
                
                setTimeout(() => {
                    const teamMessage = entry.target.querySelector('.massage-team');
                    teamMessage.classList.add('show-message');
                }, 700);
                
                observer.unobserve(entry.target);
            }
        });
    }, {threshold: 0.1});

    observer.observe(contactSection);
});