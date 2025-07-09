document.addEventListener('DOMContentLoaded', function() {
  // عناصر DOM
  const openModalBtn = document.getElementById('openProductModal');
  const closeModalBtn = document.getElementById('closeModal');
  const cancelBtn = document.getElementById('cancelBtn');
  const modal = document.getElementById('addProductModal');
  const modalContent = document.querySelector('.custom-modal-content');
  const productImage = document.getElementById('productImage');
  const imagePreview = document.getElementById('imagePreview');
  const featuresContainer = document.getElementById('featuresContainer');
  const saveProductBtn = document.getElementById('saveProduct');

  // فتح النافذة المنبثقة مع تأثير
  openModalBtn.addEventListener('click', function() {
    modal.classList.add('active');
    setTimeout(() => {
      modalContent.style.transform = 'translateY(0)';
      modalContent.style.opacity = '1';
    }, 10);
  });

  // إغلاق النافذة المنبثقة مع تأثير
  function closeModal() {
    modalContent.style.transform = 'translateY(-50px)';
    modalContent.style.opacity = '0';
    
    setTimeout(() => {
      modal.classList.remove('active');
    }, 300);
  }

  closeModalBtn.addEventListener('click', closeModal);
  cancelBtn.addEventListener('click', closeModal);

  // إغلاق النافذة عند النقر خارجها
  window.addEventListener('click', function(event) {
    if (event.target === modal) {
      closeModal();
    }
  });

  // معاينة الصورة
  productImage.addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
      const reader = new FileReader();
      reader.onload = function(event) {
        imagePreview.style.display = 'block';
        imagePreview.src = event.target.result;
        imagePreview.style.opacity = '0';
        setTimeout(() => {
          imagePreview.style.opacity = '1';
          imagePreview.style.transition = 'opacity 0.3s ease';
        }, 10);
      };
      reader.readAsDataURL(e.target.files[0]);
    }
  });

  // إضافة ميزة جديدة مع تأثير
  featuresContainer.addEventListener('click', function(e) {
    if (e.target.classList.contains('add-feature-btn') || 
        e.target.parentElement.classList.contains('add-feature-btn')) {
      const newFeature = document.createElement('div');
      newFeature.className = 'feature-item adding';
      newFeature.innerHTML = `
        <button type="button" class="remove-feature-btn"><i class="bi bi-dash"></i></button>
        <input type="text" name="features[]" placeholder="ادخل الميزة" class="feature-input">
      `;
      featuresContainer.appendChild(newFeature);
      
      setTimeout(() => {
        newFeature.classList.remove('adding');
      }, 10);
    }
    
    // إزالة ميزة مع تأثير
    if (e.target.classList.contains('remove-feature-btn') || 
        e.target.parentElement.classList.contains('remove-feature-btn')) {
      const featureItem = e.target.closest('.feature-item');
      featureItem.classList.add('removing');
      
      setTimeout(() => {
        featureItem.remove();
      }, 300);
    }
  });

  // تأثير عند الضغط على الأزرار
  const buttons = document.querySelectorAll('.custom-btn');
  buttons.forEach(button => {
    button.addEventListener('mousedown', function() {
      this.style.transform = 'scale(0.98)';
    });
    
    button.addEventListener('mouseup', function() {
      this.style.transform = 'scale(1)';
    });
    
    button.addEventListener('mouseleave', function() {
      this.style.transform = 'scale(1)';
    });
  });

  // حفظ المنتج
  saveProductBtn.addEventListener('click', function() {
    const form = document.getElementById('productForm');
    const formData = new FormData(form);
    
    // تأثير أثناء التحميل
    saveProductBtn.innerHTML = '<i class="bi bi-arrow-repeat"></i> جاري الحفظ...';
    saveProductBtn.style.opacity = '0.7';
    saveProductBtn.disabled = true;
    
    fetch('../api/save_product.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // تأثير النجاح
        saveProductBtn.innerHTML = '<i class="bi bi-check2"></i> تم الحفظ!';
        saveProductBtn.style.backgroundColor = '#2e7d32';
        
        setTimeout(() => {
          closeModal();
          form.reset();
          imagePreview.style.display = 'none';
          saveProductBtn.innerHTML = 'حفظ المنتج';
          saveProductBtn.style.backgroundColor = '';
          saveProductBtn.style.opacity = '1';
          saveProductBtn.disabled = false;
          // يمكنك هنا تحديث الجدول أو الصفحة حسب الحاجة
        }, 1000);
      } else {
        alert('حدث خطأ: ' + data.message);
        saveProductBtn.innerHTML = 'حفظ المنتج';
        saveProductBtn.style.opacity = '1';
        saveProductBtn.disabled = false;
      }
    })
    .catch(error => {
      alert('حدث خطأ في الاتصال بالخادم');
      console.error('Error:', error);
      saveProductBtn.innerHTML = 'حفظ المنتج';
      saveProductBtn.style.opacity = '1';
      saveProductBtn.disabled = false;
    });
  });
});