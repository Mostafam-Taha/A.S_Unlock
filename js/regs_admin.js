// تأثيرات إضافية عند التركيز على الحقول
const inputs = document.querySelectorAll('.input-group input');
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
});