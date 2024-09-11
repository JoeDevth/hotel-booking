<?php
require_once 'config.php';

// Create
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_customer'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $sql = "INSERT INTO customers (name, email, phone, address) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $phone, $address);

    if ($stmt->execute()) {
        echo "New customer added successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Read
$sql = "SELECT * FROM customers";
$result = $conn->query($sql);

// Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_customer'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $sql = "UPDATE customers SET name=?, email=?, phone=?, address=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $name, $email, $phone, $address, $id);

    if ($stmt->execute()) {
        echo "Customer updated successfully";
    } else {
        echo "Error updating customer: " . $stmt->error;
    }
    $stmt->close();
}

// Delete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_customer'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM customers WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Customer deleted successfully";
    } else {
        echo "Error deleting customer: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการข้อมูลลูกค้า - ระบบจองโรงแรม</title>
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
                <li><a href="logout.php">ออกจากระบบ</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>จัดการข้อมูลลูกค้า</h1>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <h2>เพิ่ม/แก้ไขข้อมูลลูกค้า</h2>
            <input type="text" name="name" placeholder="ชื่อ-นามสกุล" required>
            <input type="email" name="email" placeholder="อีเมล" required>
            <input type="text" name="phone" placeholder="เบอร์โทรศัพท์" required>
            <textarea name="address" placeholder="ที่อยู่" required></textarea>
            <button type="submit" name="add_customer">เพิ่มลูกค้า</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ชื่อ-นามสกุล</th>
                    <th>อีเมล</th>
                    <th>เบอร์โทรศัพท์</th>
                    <th>ที่อยู่</th>
                    <th>การดำเนินการ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["name"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td>" . $row["phone"] . "</td>";
                        echo "<td>" . $row["address"] . "</td>";
                        echo "<td>
                                <form method='post' action='" . $_SERVER['PHP_SELF'] . "'>
                                    <input type='hidden' name='id' value='" . $row["id"] . "'>
                                    <input type='text' name='name' value='" . $row["name"] . "' required>
                                    <input type='email' name='email' value='" . $row["email"] . "' required>
                                    <input type='text' name='phone' value='" . $row["phone"] . "' required>
                                    <textarea name='address' required>" . $row["address"] . "</textarea>
                                    <button type='submit' name='update_customer'>แก้ไข</button>
                                    <br>
                                    <button type='submit' name='delete_customer' onclick='return confirm(\"คุณแน่ใจหรือไม่ที่จะลบลูกค้าคนนี้?\");'>ลบ</button>
                                </form>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>ไม่พบข้อมูลลูกค้า</td></tr>";
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