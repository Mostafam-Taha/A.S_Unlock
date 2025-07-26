document.addEventListener('DOMContentLoaded', function() {
    // Password toggle functionality
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    
    if (togglePassword && password) {
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye-slash');
            this.querySelector('i').classList.toggle('fa-eye');
            
            // Add animation
            this.classList.add('animate__animated', 'animate__pulse');
            setTimeout(() => {
                this.classList.remove('animate__animated', 'animate__pulse');
            }, 500);
        });
    }
    
    // Google Sign In functionality
    const googleSignIn = document.querySelector('#googleSignIn');
    
    if (googleSignIn) {
        googleSignIn.addEventListener('click', function() {
            // Add click animation
            this.classList.add('animate__animated', 'animate__rubberBand');
            setTimeout(() => {
                this.classList.remove('animate__animated', 'animate__rubberBand');
            }, 1000);
            
            // Implement Google Sign In logic here
            console.log('Google Sign In clicked');
        });
    }
    
    // Form elements hover effects
    const formItems = document.querySelectorAll('.form-item');
    formItems.forEach(item => {
        item.addEventListener('mouseenter', () => {
            item.style.transform = 'translateY(-3px)';
        });
        item.addEventListener('mouseleave', () => {
            item.style.transform = 'translateY(0)';
        });
    });
    
    // Login button hover effect
    const loginBtn = document.querySelector('.login-btn');
    if (loginBtn) {
        loginBtn.addEventListener('mouseenter', () => {
            loginBtn.style.transform = 'translateY(-3px)';
        });
        loginBtn.addEventListener('mouseleave', () => {
            loginBtn.style.transform = 'translateY(0)';
        });
    }
    
    // Load Google Identity Services
    function loadGoogleIdentity() {
        const script = document.createElement('script');
        script.src = 'https://accounts.google.com/gsi/client';
        script.async = true;
        script.defer = true;
        document.body.appendChild(script);
    }
    
    loadGoogleIdentity();
});