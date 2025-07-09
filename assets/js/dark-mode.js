// dark-mode.js
document.addEventListener('DOMContentLoaded', function() {
    const darkModeToggle = document.querySelector('.dark-mode-toggle');
    const body = document.body;

    // تحقق من حالة Dark Mode من localStorage
    if (localStorage.getItem('darkMode')) {
        body.classList.add('dark-mode');
    }

    // تبديل Dark Mode عند النقر
    darkModeToggle.addEventListener('click', function() {
        body.classList.toggle('dark-mode');
        
        // حفظ الحالة في localStorage
        if (body.classList.contains('dark-mode')) {
            localStorage.setItem('darkMode', 'enabled');
        } else {
            localStorage.removeItem('darkMode');
        }
    });
});