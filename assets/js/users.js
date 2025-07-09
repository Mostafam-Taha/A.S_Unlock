function toggleDropdown(btn) {
    // Close all other open dropdowns
    document.querySelectorAll('.dropdown.show').forEach(function(openDropdown) {
        if (openDropdown !== btn.parentElement) {
            openDropdown.classList.remove('show');
        }
    });
    
    // Toggle current dropdown
    btn.parentElement.classList.toggle('show');
}

// Close dropdown when clicking outside
window.onclick = function(event) {
    if (!event.target.matches('.dropdown-btn') && !event.target.closest('.dropdown-btn')) {
        document.querySelectorAll('.dropdown.show').forEach(function(openDropdown) {
            openDropdown.classList.remove('show');
        });
    }
}














function toggleBanUser(userId, isBanned) {
    // تأكيد الإجراء
    const confirmation = confirm(`هل أنت متأكد من أنك تريد ${isBanned ? 'فك حظر' : 'حظر'} هذا المستخدم؟`);
    if (!confirmation) return;

    // إرسال طلب AJAX
    fetch('../api/ban_user.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `user_id=${userId}&action=${isBanned ? 'unban' : 'ban'}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            // تحديث الصفحة لرؤية التغييرات
            location.reload();
        } else {
            alert(data.message || 'حدث خطأ ما');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء معالجة الطلب');
    });
}