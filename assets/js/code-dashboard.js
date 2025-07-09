// عرض تفاصيل المنتج
async function showProductDetails(productId) {
    try {
        const response = await fetch(`../api/get_product_details.php?id=${productId}`);
        const product = await response.json();
        
        // بناء محتوى التفاصيل
        let featuresHtml = '';
        const features = JSON.parse(product.features || '[]');
        features.forEach(feat => {
            featuresHtml += `<li>${feat}</li>`;
        });
        
        const detailsHtml = `
            <div class="product-details-grid">
                <div class="product-image">
                    ${product.image_path ? `<img src="${product.image_path}" alt="${product.product_name}">` : 'لا توجد صورة'}
                </div>
                <div class="product-info">
                    <h4>${product.product_name}</h4>
                    <p><strong>السعر:</strong> ${product.price} ج</p>
                    ${product.discount ? `<p><strong>الخصم:</strong> ${product.discount} ج</p>` : ''}
                    <p><strong>الوصف:</strong> ${product.description}</p>
                    
                    <!-- حقل الإرشادات الجديد -->
                    <div class="form-group">
                        <label for="productInstructions"><strong>إرشادات الاستخدام:</strong></label>
                        <textarea id="productInstructions" class="custom-textarea" placeholder="أدخل إرشادات استخدام المنتج">${product.instructions || ''}</textarea>
                    </div>
                    
                    <p><strong>المميزات:</strong></p>
                    <ul class="features-list">${featuresHtml}</ul>
                    
                    <!-- Checkbox للحالة الجديدة -->
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="isSpecialOffer" class="custom-checkbox" 
                            ${product.is_special_offer ? 'checked' : ''}>
                        <label for="isSpecialOffer">عرض خاص (سيظهر في الصفحة الرئيسية)</label>
                    </div>
                    
                    <p><strong>الحالة:</strong> ${product.is_published ? 'منشور' : 'معطل'}</p>
                    <p><strong>تاريخ الإنشاء:</strong> ${new Date(product.created_at).toLocaleString()}</p>
                </div>
                
                <div class="complete-data-section">
                    <div class="data-down">
                        <h4>إكمال البيانات</h4>
                        <div><i class="bi bi-chevron-down arrow-icon"></i></div>
                    </div>
                    <div class="more-product" style="display: none;">
                        <div class="plan-selection">
                            <div class="section-title"><span>اختر الخطة</span></div>
                            <div class="select">
                                <select id="planType">
                                    <option value="1">الأساسية</option>
                                    <option value="2">الاكثر طلبًا</option>
                                    <option value="3">احترافية</option>
                                    <option value="4">أخرى</option>
                                </select>
                            </div>
                            <div class="img-svg" id="planIcon">
                                <i class="bi bi-star-fill basic-plan"></i>
                            </div>
                            <div class="plan-inputs">
                                <div><input type="text" id="planName" placeholder="اسم الخطة" value=""></div>
                                <div><input type="text" id="planPrice" placeholder="سعر الخطة" value=""></div>
                            </div>
                            <div class="plan-features">
                                <label>مميزات الخطة</label>
                                <div id="planFeaturesContainer">
                                    <div class="feature-input-group">
                                        <button type="button" class="add-feature-btn" onclick="addPlanFeature()"><i class="bi bi-plus"></i></button>
                                        <input type="text" name="planFeatures[]" placeholder="ادخل ميزة الخطة" class="feature-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <button class="delete-btn" onclick="deleteProduct(${product.id})">حذف المنتج</button>
                    <button> <a href="../api/edit_product.php?id=${product.id}" class="edit-btn">تعديل المنتج</a> </button>

                    <button class="save-btn" onclick="saveAdditionalData(${product.id})">حفظ البيانات الإضافية</button>
                </div>
            </div>
        `;
        
        document.getElementById('productDetailsContent').innerHTML = detailsHtml;
        document.getElementById('productDetailsModal').style.display = 'block';
        
        // تحديث الأيقونة حسب نوع الخطة
        document.getElementById('planType').addEventListener('change', function() {
            updatePlanIcon(this.value);
        });
        
    } catch (error) {
        console.error('Error:', error);
        alert('حدث خطأ أثناء جلب بيانات المنتج');
    }
}

// إغلاق نافذة التفاصيل
function closeDetailsModal() {
    document.getElementById('productDetailsModal').style.display = 'none';
}

// إضافة ميزة للخطة
function addPlanFeature() {
    const container = document.getElementById('planFeaturesContainer');
    const newFeature = document.createElement('div');
    newFeature.className = 'feature-input-group';
    newFeature.innerHTML = `
        <button type="button" class="remove-feature-btn" onclick="this.parentElement.remove()"><i class="bi bi-dash"></i></button>
        <input type="text" name="planFeatures[]" placeholder="ادخل ميزة الخطة" class="feature-input">
    `;
    container.appendChild(newFeature);
}

// تحديث أيقونة الخطة
function updatePlanIcon(planType) {
    const iconContainer = document.getElementById('planIcon');
    let iconClass = '';
    
    switch(planType) {
        case '1': iconClass = 'bi-star-fill basic-plan'; break;
        case '2': iconClass = 'bi-gem popular-plan'; break;
        case '3': iconClass = 'bi-award pro-plan'; break;
        default: iconClass = 'bi-question-circle other-plan';
    }
    
    iconContainer.innerHTML = `<i class="bi ${iconClass}"></i>`;
}

// حذف المنتج
async function deleteProduct(productId) {
    if (confirm('هل أنت متأكد من حذف هذا المنتج؟')) {
    try {
            const response = await fetch(`../api/delete_product.php?id=${productId}`, { method: 'DELETE' });
        const result = await response.json();
        
        if (result.success) {
            alert('تم حذف المنتج بنجاح');
            closeDetailsModal();
                // تحديث الجدول
                document.querySelector(`tr[data-product-id="${productId}"]`).remove();
            } else {
                alert('حدث خطأ: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('حدث خطأ أثناء حذف المنتج');
        }
    }
}

// حفظ البيانات الإضافية
async function saveAdditionalData(productId) {
    const planType = document.getElementById('planType').value;
    const planName = document.getElementById('planName').value;
    const planPrice = document.getElementById('planPrice').value;
    const instructions = document.getElementById('productInstructions').value;
    const isSpecialOffer = document.getElementById('isSpecialOffer').checked ? 1 : 0;
    
    const planFeatures = [];
    document.querySelectorAll('#planFeaturesContainer input[type="text"]').forEach(input => {
        if (input.value.trim() !== '') {
            planFeatures.push(input.value.trim());
        }
    });
    
    try {
        const response = await fetch('../api/save_additional_data.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                productId,
                planType,
                planName,
                planPrice,
                planFeatures,
                instructions,
                isSpecialOffer
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('تم حفظ البيانات بنجاح');
        } else {
            alert('حدث خطأ: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('حدث خطأ أثناء حفظ البيانات');
    }
}

// تعديل المنتج
function editProduct(productId) {
    // يمكنك تنفيذ نفس منطق العرض ولكن مع حقول قابلة للتعديل
    // أو توجيه المستخدم إلى صفحة التعديل
    window.location.href = `edit_product.php?id=${productId}`;
}

// 
// 
// <div><i class="bi bi-chevron-down arrow-icon"></i></div>
document.addEventListener('click', function(e) {
    if (e.target.closest('.data-down')) {
        const arrowIcon = e.target.closest('.data-down').querySelector('.arrow-icon');
        const moreProductSection = e.target.closest('.complete-data-section').querySelector('.more-product');
        
        // تبديل حالة العرض
        if (moreProductSection.style.display === 'none') {
            // فتح القسم بتأثير لطيف
            moreProductSection.style.display = 'block';
            moreProductSection.style.animation = 'fadeIn 0.3s ease-in-out';
            arrowIcon.classList.remove('bi-chevron-down');
            arrowIcon.classList.add('bi-chevron-up');
        } else {
            // إغلاق القسم بتأثير لطيف
            moreProductSection.style.animation = 'fadeOut 0.3s ease-in-out';
            setTimeout(() => {
                moreProductSection.style.display = 'none';
            }, 300);
            arrowIcon.classList.remove('bi-chevron-up');
            arrowIcon.classList.add('bi-chevron-down');
        }
    }
});

// دالة شريط البحث
function searchProducts() {
    const input = document.getElementById('search-table-product');
    const filter = input.value.toUpperCase();
    const table = document.querySelector('table');
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        const td = tr[i].getElementsByTagName('td')[0]; // العمود الأول (اسم المنتج)
        if (td) {
            const txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().includes(filter)) { // استخدام includes بدلاً من indexOf
                tr[i].style.display = '';
            } else {
                tr[i].style.display = 'none';
            }
        }
    }
}

// أو بدلاً من oninput في HTML، يمكنك إضافة EventListener هنا
document.getElementById('search-table-product').addEventListener('input', searchProducts);