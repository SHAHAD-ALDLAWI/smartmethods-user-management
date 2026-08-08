// دالة لتحميل المستخدمين من قاعدة البيانات
function loadUsers() {
    fetch('api/get_users.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = ''; // مسح الجدول الحالي

            if (data.success && data.users.length > 0) {
                data.users.forEach(user => {
                    const row = document.createElement('tr');
                    const statusClass = user.status == 0 ? 'status-0' : 'status-1';
                    const statusText = user.status == 0 ? 'Inactive' : 'Active';

                    row.innerHTML = `
                        <td>${user.id}</td>
                        <td>${user.name}</td>
                        <td>${user.age}</td>
                        <td><span class="${statusClass}">${statusText}</span></td>
                        <td><button class="btn-toggle" onclick="toggleStatus(${user.id}, ${user.status})">Toggle</button></td>
                    `;
                    tableBody.appendChild(row);
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding:20px;">No records found</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error loading users:', error);
            showMessage('Error loading users', 'error');
        });
}

// دالة لإضافة مستخدم جديد
document.getElementById('userForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const name = document.getElementById('name').value.trim();
    const age = document.getElementById('age').value.trim();

    if (!name || !age) {
        showMessage('Please fill in all fields', 'error');
        return;
    }

    // إرسال البيانات إلى backend
    fetch('api/add_user.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ name: name, age: age })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('User added successfully!', 'success');
            document.getElementById('userForm').reset();
            loadUsers(); // إعادة تحميل الجدول
        } else {
            showMessage(data.message || 'Error adding user', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Error adding user', 'error');
    });
});

// دالة لتبديل حالة المستخدم (Toggle Status)
function toggleStatus(userId, currentStatus) {
    const newStatus = currentStatus == 0 ? 1 : 0;

    fetch('api/update_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: userId, status: newStatus })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadUsers(); // إعادة تحميل الجدول بدون refresh
        } else {
            showMessage('Error updating status', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Error updating status', 'error');
    });
}

// دالة لعرض الرسائل
function showMessage(text, type) {
    const messageElement = document.getElementById('message');
    messageElement.textContent = text;
    messageElement.className = `message ${type}`;
    
    // إخفاء الرسالة بعد 3 ثوان
    setTimeout(() => {
        messageElement.className = 'message';
    }, 3000);
}

// تحميل المستخدمين عند فتح الصفحة
document.addEventListener('DOMContentLoaded', function() {
    loadUsers();
});
