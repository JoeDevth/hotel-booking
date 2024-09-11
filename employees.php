<?php
require_once 'config.php';

// Create
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_employee'])) {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO employees (name, position, email, phone) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $position, $email, $phone);

    if ($stmt->execute()) {
        echo "New employee added successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Read
$sql = "SELECT * FROM employees";
$result = $conn->query($sql);

// Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_employee'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $position = $_POST['position'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "UPDATE employees SET name=?, position=?, email=?, phone=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $position, $email, $phone, $id);

    if ($stmt->execute()) {
        echo "Employee updated successfully";
    } else {
        echo "Error updating employee: " . $stmt->error;
    }
    $stmt->close();
}

// Delete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_employee'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM employees WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Employee deleted successfully";
    } else {
        echo "Error deleting employee: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการข้อมูลพนักงาน - ระบบจองโรงแรม</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">หน้าแรก</a></li>
                <li><a href="rooms.php">ข้อมูลห้อง</a></li>
                <li><a href="employees.php">พนักงาน</a></li>
                <li><a href="customers.php">ลูกค้า</a></li>
                <li><a href="bookings.php">การจอง</a></li>
                <li><a href="login.html">เข้าสู่ระบบ</a></li>
                <li><a href="register.html">สมัครสมาชิก</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>จัดการข้อมูลพนักงาน</h1>

        <form id="employee-form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <h2>เพิ่ม/แก้ไขข้อมูลพนักงาน</h2>
            <input type="text" name="name" placeholder="ชื่อ-นามสกุล" required>
            <input type="text" name="position" placeholder="ตำแหน่ง" required>
            <input type="email" name="email" placeholder="อีเมล" required>
            <input type="text" name="phone" placeholder="เบอร์โทรศัพท์" required>
            <br>
            <button type="submit" name="add_employee">เพิ่มพนักงาน</button>
        </form>

        <table id="employee-table">
            <thead>
                <tr>
                    <th>ชื่อ-นามสกุล</th>
                    <th>ตำแหน่ง</th>
                    <th>อีเมล</th>
                    <th>เบอร์โทรศัพท์</th>
                    <th>การดำเนินการ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["name"] . "</td>";
                        echo "<td>" . $row["position"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td>" . $row["phone"] . "</td>";
                        echo "<td>
                                <form method='post' action='" . $_SERVER['PHP_SELF'] . "'>
                                    <input type='hidden' name='id' value='" . $row["id"] . "'>
                                    <input type='text' name='name' value='" . $row["name"] . "' required>
                                    <input type='text' name='position' value='" . $row["position"] . "' required>
                                    <input type='email' name='email' value='" . $row["email"] . "' required>
                                    <input type='text' name='phone' value='" . $row["phone"] . "' required>
                                    
                                    <button type='submit' name='update_employee'>แก้ไข</button>
                                    <br>
                                    <button type='submit' name='delete_employee' onclick='return confirm(\"คุณแน่ใจหรือไม่ที่จะลบพนักงานคนนี้?\");'>ลบ</button>
                                </form>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>ไม่พบข้อมูลพนักงาน</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>&copy; 2024 ระบบจองโรงแรม. สงวนลิขสิทธิ์.</p>
    </footer>
</body>
</html>