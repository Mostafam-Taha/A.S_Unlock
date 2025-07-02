// تحقق من قوة كلمة المرور - نسخة محسنة
function checkPasswordStrength(password) {
    const strengthBar = document.getElementById('passwordBar');
    const strengthText = document.getElementById('passwordText');
    
    if (!password) {
        strengthBar.style.width = '0%';
        strengthText.textContent = 'قوة كلمة المرور';
        strengthText.style.color = '#666';
        return 0; // إرجاع 0 إذا كانت فارغة
    }
    
    const strength = calculatePasswordStrength(password);
    let text = '';
    let color = '';
    
    switch(strength) {
        case 0:
        case 1:
            text = "ضعيفة جداً (غير آمنة)";
            color = "#e74c3c";
            break;
        case 2:
            text = "ضعيفة (يجب تحسينها)";
            color = "#e67e22";
            break;
        case 3:
            text = "متوسطة (جيدة ولكن يمكن تحسينها)";
            color = "#f1c40f";
            break;
        case 4:
            text = "قوية (جيدة جدًا)";
            color = "#2ecc71";
            break;
        case 5:
            text = "قوية جداً (ممتازة)";
            color = "#27ae60";
            break;
    }
    
    strengthBar.style.width = (strength * 20) + '%';
    strengthBar.style.backgroundColor = color;
    strengthBar.style.transition = 'width 0.3s ease, background-color 0.3s ease';
    strengthText.textContent = text;
    strengthText.style.color = color;
    
    return strength;
}

function validateForm() {
    const username = document.getElementById('username').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const question = document.getElementById('securityQuestion').value;
    const answer = document.getElementById('securityAnswer').value.trim();
    
    if (!username) {
        showError('اسم المستخدم مطلوب');
        return false;
    }
    
    if (!/^[a-zA-Z0-9]+$/.test(username)) {
        showError('اسم المستخدم يجب أن يحتوي على أحرف إنجليزية وأرقام فقط بدون مسافات');
        return false;
    }
    
    if (!email) {
        showError('البريد الإلكتروني مطلوب');
        return false;
    }
    
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showError('البريد الإلكتروني غير صالح');
        return false;
    }
    
    if (!password) {
        showError('كلمة المرور مطلوبة');
        return false;
    }
    
    if (password.length < 8) {
        showError('كلمة المرور يجب أن تحتوي على 8 أحرف على الأقل');
        return false;
    }
    
    const passwordStrength = calculatePasswordStrength(password);
    if (passwordStrength < 3) {
        showWarning('كلمة المرور ضعيفة، يوصى باستخدام كلمة مرور أقوى');
        // يمكنك إما:
        // 1. منع التسجيل تماماً: return false;
        // 2. السماح مع التحذير (كما في هذا المثال)
    }
    
    if (!question) {
        showError('يرجى اختيار سؤال الأمان');
        return false;
    }
    
    if (!answer) {
        showError('إجابة سؤال الأمان مطلوبة');
        return false;
    }
    
    return true;
}

function calculatePasswordStrength(password) {
    let strength = 0;
    
    const hasMinLength = password.length >= 8;
    const hasMixedCase = /[a-z]/.test(password) && /[A-Z]/.test(password);
    const hasNumbers = /[0-9]/.test(password);
    const hasSpecialChars = /[!@#$%^&*(),.?":{}|<>]/.test(password);
    const isVeryLong = password.length >= 12;
    
    strength += hasMinLength ? 1 : 0;
    strength += hasMixedCase ? 1 : 0;
    strength += hasNumbers ? 1 : 0;
    strength += hasSpecialChars ? 1 : 0;
    strength += isVeryLong ? 1 : 0;
    
    return strength;
}

function showWarning(message) {
    const warningDiv = document.createElement('div');
    warningDiv.className = 'warning-message';
    warningDiv.innerHTML = `
        <i class="fas fa-exclamation-triangle"></i>
        <span>${message}</span>
    `;
    warningDiv.style.color = '#f39c12';
    warningDiv.style.marginTop = '10px';
    warningDiv.style.padding = '10px';
    warningDiv.style.borderRadius = '4px';
    warningDiv.style.backgroundColor = '#fef5e7';
    warningDiv.style.display = 'flex';
    warningDiv.style.alignItems = 'center';
    warningDiv.style.gap = '10px';
    
    const oldWarning = document.querySelector('.warning-message');
    if (oldWarning) oldWarning.remove();
    
    const form = document.getElementById('signupForm');
    form.prepend(warningDiv);
    
    setTimeout(() => {
        warningDiv.style.transition = 'opacity 0.5s ease';
        warningDiv.style.opacity = '0';
        setTimeout(() => warningDiv.remove(), 500);
    }, 5000);
}

function showError(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    errorDiv.style.color = '#e74c3c';
    errorDiv.style.marginTop = '10px';
    errorDiv.style.padding = '10px';
    errorDiv.style.borderRadius = '4px';
    errorDiv.style.backgroundColor = '#fadbd8';
    
    const oldError = document.querySelector('.error-message');
    if (oldError) oldError.remove();
    
    const form = document.getElementById('signupForm');
    form.prepend(errorDiv);
    
    setTimeout(() => {
        errorDiv.style.transition = 'opacity 0.5s ease';
        errorDiv.style.opacity = '0';
        setTimeout(() => errorDiv.remove(), 500);
    }, 5000);
}

document.getElementById('signupForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (!validateForm()) return;
    
    const formData = {
        username: document.getElementById('username').value.trim(),
        email: document.getElementById('email').value.trim(),
        password: document.getElementById('password').value,
        security_question_id: document.getElementById('securityQuestion').value,
        security_answer: document.getElementById('securityAnswer').value.trim()
    };
    
    try {
        const submitBtn = document.querySelector('.signup-btn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري إنشاء الحساب...';
        
        const response = await fetch('../includes/admin_signup.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            // عرض رسالة نجاح
            const successDiv = document.createElement('div');
            successDiv.className = 'success-message';
            successDiv.innerHTML = `
                <i class="fas fa-check-circle"></i>
                <span>${result.message || 'تم إنشاء الحساب بنجاح!'}</span>
            `;
            successDiv.style.color = '#27ae60';
            successDiv.style.marginTop = '10px';
            successDiv.style.padding = '10px';
            successDiv.style.borderRadius = '4px';
            successDiv.style.backgroundColor = '#d5f5e3';
            successDiv.style.display = 'flex';
            successDiv.style.alignItems = 'center';
            successDiv.style.gap = '10px';
            
            const form = document.getElementById('signupForm');
            form.prepend(successDiv);
            
            setTimeout(() => {
                window.location.href = 'logs.php';
            }, 3000);
        } else {
            showError(result.message || 'حدث خطأ أثناء إنشاء الحساب');
        }
    } catch (error) {
        showError('حدث خطأ في الاتصال بالخادم: ' + error.message);
    } finally {
        const submitBtn = document.querySelector('.signup-btn');
        submitBtn.disabled = false;
        submitBtn.textContent = 'إنشاء الحساب';
    }
});

// تأثيرات إضافية محسنة
const inputs = document.querySelectorAll('.input-group input, .input-group select');
inputs.forEach(input => {
    const icon = input.parentElement.querySelector('i');
    
    input.addEventListener('focus', function() {
        this.parentElement.style.borderColor = '#3498db';
        icon.style.color = '#3498db';
        icon.style.transform = 'translateY(-50%) scale(1.2)';
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.style.borderColor = '#ddd';
        icon.style.color = '#777';
        icon.style.transform = 'translateY(-50%) scale(1)';
    });
    
    // إضافة تأثير عند وجود قيمة
    input.addEventListener('input', function() {
        if (this.value) {
            this.parentElement.querySelector('i').style.color = '#2ecc71';
        } else {
            this.parentElement.querySelector('i').style.color = '#777';
        }
    });
});