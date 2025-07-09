// فتح وإغلاق المودال
const modal = document.getElementById('productModal');
const closeBtn = document.querySelector('.close-modal');

function openProductModal(productId) {
    fetchProductPlans(productId);
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

closeBtn.onclick = function() {
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// جلب خطط المنتج من قاعدة البيانات
function fetchProductPlans(productId) {
    fetch(`get_product_plans.php?product_id=${productId}`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('modalPlansContainer');
            
            if (data.length === 0) {
                container.innerHTML = `
                    <div class="no-plans">
                        <i class="bi bi-exclamation-circle"></i>
                        <h3>لا يوجد خطط متاحة لهذا المنتج</h3>
                        <p>يرجى التواصل معنا لمعرفة التفاصيل</p>
                    </div>
                `;
            } else {
                let plansHTML = '<div class="plans-grid">';
                data.forEach(plan => {
                    // تحديد الأيقونة حسب نوع الخطة
                    let planIcon, planClass;
                    switch(plan.plan_type) {
                        case '1':
                            planIcon = 'bi-star';
                            planClass = 'basic-plan';
                            break;
                        case '2':
                            planIcon = 'bi-heart';
                            planClass = 'popular-plan';
                            break;
                        case '3':
                            planIcon = 'bi-award';
                            planClass = 'pro-plan';
                            break;
                        default:
                            planIcon = 'bi-file-earmark';
                            planClass = '';
                    }

                    let featuresHTML = '';
                    const features = JSON.parse(plan.plan_features);
                    
                    if (features && features.length > 0) {
                        features.forEach(feature => {
                            featuresHTML += `
                                <li class="plan-feature">
                                    <i class="bi bi-check-circle"></i>
                                    <span>${feature}</span>
                                </li>
                            `;
                        });
                    }
                    
                    // إضافة التعليمات إذا كانت موجودة
                    let instructionsHTML = '';
                    if (plan.instructions && plan.instructions.trim() !== '') {
                        instructionsHTML = `
                            <div class="plan-instructions">
                                <h4><i class="bi bi-info-circle"></i> تعليمات مهمة:</h4>
                                <p>${plan.instructions}</p>
                            </div>
                        `;
                    }
                    
                    plansHTML += `
                        <div class="plan-card ${planClass}">
                            <div class="plan-icon">
                                <i class="bi ${planIcon}"></i>
                            </div>
                            <h3 class="plan-name">${plan.plan_name}</h3>
                            <div class="plan-price">${plan.plan_price} جنية</div>
                            <ul class="plan-features">
                                ${featuresHTML}
                            </ul>
                            <button class="btn-plan" data-plan-id="${plan.plan_id}" data-product-id="${productId}">طلب الخدمة</button>
                        </div>
                    `;
                });
                plansHTML += '</div>';
                container.innerHTML = plansHTML;
                
                // إضافة أحداث النقر للأزرار بعد إنشائها
                addPlanButtonEvents();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('modalPlansContainer').innerHTML = `
                <div class="no-plans">
                    <i class="bi bi-exclamation-triangle"></i>
                    <h3>حدث خطأ في جلب البيانات</h3>
                    <p>يرجى المحاولة مرة أخرى</p>
                </div>
            `;
        });
}


function addPlanButtonEvents() {
    const planButtons = document.querySelectorAll('.btn-plan');
    
    planButtons.forEach(button => {
        button.addEventListener('click', function() {
            const planId = this.getAttribute('data-plan-id');
            const productId = this.getAttribute('data-product-id');
            
            // توجيه المستخدم إلى صفحة الدفع مع إرسال معرّفي الخطة والمنتج
            window.location.href = `checkout.php?product_id=${productId}&plan_id=${planId}`;
        });
    });
}