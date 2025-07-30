// ==========================================
// Teams Index AddTeams
// ==========================================
document.getElementById('saveTeam').addEventListener('click', function () {
    const formData = new FormData(document.getElementById('teamForm'));

    fetch('../api/save_team.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم الحفظ بنجاح');
                $('#teamModal').modal('hide');
                location.reload();
            } else {
                alert('حدث خطأ: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء الاتصال بالخادم');
        });
});

// ==========================================
// Teams Get Team
// ==========================================
document.addEventListener('DOMContentLoaded', function () {
    fetchTeams();
});

function fetchTeams() {
    fetch('../api/get_teams.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                populateTable(data.data);
            } else {
                console.error('Error:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}

function populateTable(teams) {
    const tbody = document.querySelector('#itemsTableTeam tbody');
    tbody.innerHTML = ''; // مسح المحتوى الحالي

    teams.forEach(team => {
        const tr = document.createElement('tr');

        // تحويل التاريخ إلى تنسيق أكثر قابلية للقراءة
        const createdAt = new Date(team.created_at);
        const formattedDate = createdAt.toLocaleDateString('ar-EG');

        tr.innerHTML = `
            <td>${team.id}</td>
            <td>${team.name}</td>
            <td>${formattedDate}</td>
            <td>${team.job_title}</td>
            <td> 
                <a href="#" class="delete-item" data-id="${team.id}">حذف</a>
            </td>
        `;

        tbody.appendChild(tr);
    });

    // إضافة معالجات الأحداث لأزرار الحذف والعرض
    document.querySelectorAll('.delete-item').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            deleteTeam(id);
        });
    });
}

function deleteTeam(id) {
    if (confirm('هل أنت متأكد من حذف هذا العضو؟')) {
        fetch(`../api/delete_team.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('تم الحذف بنجاح');
                    fetchTeams(); // تحديث الجدول
                } else {
                    alert('حدث خطأ: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    }
}