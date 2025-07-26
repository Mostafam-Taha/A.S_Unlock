// dark-mode.js
document.addEventListener('DOMContentLoaded', function() {
    const darkModeToggle = document.querySelector('.dark-mode-toggle');
    const body = document.body;

    if (localStorage.getItem('darkMode')) {
        body.classList.add('dark-mode');
    }

    darkModeToggle.addEventListener('click', function() {
        body.classList.toggle('dark-mode');
        
        if (body.classList.contains('dark-mode')) {
            localStorage.setItem('darkMode', 'enabled');
        } else {
            localStorage.removeItem('darkMode');
        }
    });
});