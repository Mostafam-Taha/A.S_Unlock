document.addEventListener('DOMContentLoaded', function() {
    const cardInboxes = document.querySelectorAll('.card-inbox');
    const viewDateSection = document.querySelector('.view-date');
    const usernameElement = viewDateSection.querySelector('.username h3');
    const descriptionElement = viewDateSection.querySelector('.dis span');
    const imageElement = viewDateSection.querySelector('.img img');
    const deleteBtn = viewDateSection.querySelector('.delete-btn');
    const featureBtn = viewDateSection.querySelector('.feature-btn');

    cardInboxes.forEach(card => {
        card.addEventListener('click', function() {
            // تحديث البيانات في قسم view-date
            usernameElement.textContent = this.dataset.name;
            descriptionElement.textContent = this.dataset.description;
            
            // تحديث الصورة إذا كانت موجودة
            if (this.dataset.image) {
                imageElement.src = this.dataset.image;
                imageElement.parentElement.style.display = 'block';
            } else {
                imageElement.parentElement.style.display = 'none';
            }
            
            // تحديث تاريخ الإنشاء بصيغة أفضل
            const createdAt = new Date(this.dataset.created);
            // يمكنك إضافة منطق لعرض التاريخ بشكل أجمل هنا
            
            // تحديث أزرار الحذف والتمييز بمعرف العنصر
            deleteBtn.dataset.id = this.dataset.id;
            featureBtn.dataset.id = this.dataset.id;
            
            // تحديث حالة زر التمييز
            if (this.dataset.featured === '1') {
                featureBtn.innerHTML = '<i class="bi bi-star-fill"></i> إلغاء التمييز';
            } else {
                featureBtn.innerHTML = '<i class="bi bi-star"></i> تمييز';
            }
            
            // إضافة تأثير مرئي للبطاقة المحددة
            cardInboxes.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // يمكنك هنا إضافة معالجات الأحداث لأزرار الحذف والتمييز
    deleteBtn.addEventListener('click', function() {
        const itemId = this.dataset.id;
        if (itemId) {
            if (confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
                // إرسال طلب حذف إلى السيرفر
                fetch(`delete_item.php?id=${itemId}`, { method: 'POST' })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // إزالة البطاقة من القائمة
                            document.querySelector(`.card-inbox[data-id="${itemId}"]`).remove();
                            // إعادة تعيين قسم العرض
                            resetViewDate();
                        }
                    });
            }
        }
    });

    featureBtn.addEventListener('click', function() {
        const itemId = this.dataset.id;
        if (itemId) {
            fetch(`toggle_feature.php?id=${itemId}`, { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // تحديث حالة الزر والبطاقة
                        const card = document.querySelector(`.card-inbox[data-id="${itemId}"]`);
                        card.dataset.featured = data.is_featured;
                        
                        if (data.is_featured) {
                            this.innerHTML = '<i class="bi bi-star-fill"></i> إلغاء التمييز';
                        } else {
                            this.innerHTML = '<i class="bi bi-star"></i> تمييز';
                        }
                    }
                });
        }
    });

    function resetViewDate() {
        usernameElement.textContent = 'اسم المستخدم';
        descriptionElement.textContent = 'وصف';
        imageElement.src = '';
        imageElement.parentElement.style.display = 'none';
        deleteBtn.dataset.id = '';
        featureBtn.dataset.id = '';
    }
});
