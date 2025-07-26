$(document).ready(function() {
    $('#questionForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            data: $(this).serialize(),
            success: function(response) {
                if(response.success) {
                    alert('تم حفظ السؤال بنجاح');
                    $('#questionModal').modal('hide');
                    $('#questionForm')[0].reset();
                    // يمكنك هنا تحديث قائمة الأسئلة إذا كنت تعرضها
                } else {
                    alert('حدث خطأ: ' + response.message);
                }
            },
            error: function() {
                alert('حدث خطأ أثناء الاتصال بالخادم');
            }
        });
    });
});
// ===================
// == Search Table ==
// ===================
function searchTable() {
    var input = document.getElementById("search-table-questions");
    var filter = input.value.toUpperCase();
    
    var table = document.getElementById("itemsTable");
    var tr = table.getElementsByTagName("tr");
    
    for (var i = 1; i < tr.length; i++) {
        var tdQuestion = tr[i].getElementsByTagName("td")[1];
        var tdAnswer = tr[i].getElementsByTagName("td")[2];
        
        var fullQuestion = tdQuestion.getAttribute("data-full-question").toUpperCase();
        var fullAnswer = tdAnswer.getAttribute("data-full-answer").toUpperCase();
        
        if (fullQuestion.indexOf(filter) > -1 || fullAnswer.indexOf(filter) > -1) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
    }
}
// ===================
// == Del Item ==
// ===================
document.addEventListener('DOMContentLoaded', function() {
    let deleteId = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
    
    // عند النقر على رابط الحذف
    document.querySelectorAll('.delete-item').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            deleteId = this.getAttribute('data-id');
            deleteModal.show();
        });
    });
    
    // عند تأكيد الحذف
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if(!deleteId) return;
        
        fetch('../api/delete_question.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + deleteId
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // عرض رسالة نجاح
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show';
                alertDiv.innerHTML = `
                    تم حذف السؤال بنجاح!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                
                document.querySelector('body').prepend(alertDiv);
                
                // إغلاق المودال وتحديث الصفحة بعد ثانية
                deleteModal.hide();
                setTimeout(() => location.reload(), 1000);
            } else {
                alert('حدث خطأ: ' + (data.message || 'تعذر حذف السؤال'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ في الاتصال بالخادم');
        });
    });
});