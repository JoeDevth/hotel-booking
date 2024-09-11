// ฟังก์ชันสำหรับจัดการฟอร์มและตารางข้อมูล
function handleForm(formId, tableId, dataKey) {
    const form = document.getElementById(formId);
    const table = document.getElementById(tableId);
    let data = JSON.parse(localStorage.getItem(dataKey)) || [];

    // แสดงข้อมูลในตาราง
    function displayData() {
        table.querySelector('tbody').innerHTML = data.map((item, index) => `
            <tr>
                ${Object.values(item).map(value => `<td>${value}</td>`).join('')}
                <td class="action-buttons">
                    <button class="edit-btn" onclick="editItem(${index})">แก้ไข</button>
                    <button class="delete-btn" onclick="deleteItem(${index})">ลบ</button>
                </td>
            </tr>
        `).join('');
    }

    // เพิ่มหรือแก้ไขข้อมูล
    form.onsubmit = (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        const item = Object.fromEntries(formData.entries());
        const index = form.querySelector('input[type="hidden"]').value;

        if (index === '') {
            data.push(item);
        } else {
            data[index] = item;
        }

        localStorage.setItem(dataKey, JSON.stringify(data));
        displayData();
        form.reset();
        form.querySelector('input[type="hidden"]').value = '';
    };

    // แก้ไขข้อมูล
    window.editItem = (index) => {
        const item = data[index];
        Object.keys(item).forEach(key => {
            const input = form.querySelector(`#${key}`);
            if (input) input.value = item[key];
        });
        form.querySelector('input[type="hidden"]').value = index;
    };

    // ลบข้อมูล
    window.deleteItem = (index) => {
        if (confirm('คุณแน่ใจหรือไม่ที่จะลบข้อมูลนี้?')) {
            data.splice(index, 1);
            localStorage.setItem(dataKey, JSON.stringify(data));
            displayData();
        }
    };

    // แสดงข้อมูลเมื่อโหลดหน้า
    displayData();
}

// เรียกใช้ฟังก์ชันสำหรับแต่ละหน้า
if (document.getElementById('room-form')) {
    handleForm('room-form', 'room-table', 'rooms');
}

if (document.getElementById('employee-form')) {
    handleForm('employee-form', 'employee-table', 'employees');
}

if (document.getElementById('customer-form')) {
    handleForm('customer-form', 'customer-table', 'customers');
}

if (document.getElementById('booking-form')) {
    handleForm('booking-form', 'booking-table', 'bookings');
}

// จัดการการเข้าสู่ระบบ
if (document.getElementById('login-form')) {
    const loginForm = document.getElementById('login-form');
    loginForm.onsubmit = (e) => {
        e.preventDefault();
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        // ตรวจสอบการเข้าสู่ระบบ (ตัวอย่างง่ายๆ)
        if (username === 'admin' && password === 'password') {
            alert('เข้าสู่ระบบสำเร็จ!');
            window.location.href = 'index.html';
        } else {
            alert('ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง');
        }
    };
}

// จัดการการสมัครสมาชิก
if (document.getElementById('register-form')) {
    const registerForm = document.getElementById('register-form');
    registerForm.onsubmit = (e) => {
        e.preventDefault();
        const username = document.getElementById('username').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm-password').value;

        if (password !== confirmPassword) {
            alert('รหัสผ่านไม่ตรงกัน');
            return;
        }

        // บันทึกข้อมูลผู้ใช้ (ตัวอย่างง่ายๆ)
        const users = JSON.parse(localStorage.getItem('users')) || [];
        users.push({ username, email, password });
        localStorage.setItem('users', JSON.stringify(users));

        alert('สมัครสมาชิกสำเร็จ!');
        window.location.href = 'login.html';
    };
}