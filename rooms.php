<?php
require_once 'config.php';

// Create
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_room'])) {
    $room_number = $_POST['room_number'];
    $room_type = $_POST['room_type'];
    $price = $_POST['price'];

    $sql = "INSERT INTO rooms (room_number, room_type, price) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssd", $room_number, $room_type, $price);

    if ($stmt->execute()) {
        echo "New room added successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Read
$sql = "SELECT * FROM rooms";
$result = $conn->query($sql);

// Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_room'])) {
    $id = $_POST['id'];
    $room_number = $_POST['room_number'];
    $room_type = $_POST['room_type'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    $sql = "UPDATE rooms SET room_number=?, room_type=?, price=?, status=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdsi", $room_number, $room_type, $price, $status, $id);

    if ($stmt->execute()) {
        echo "Room updated successfully";
    } else {
        echo "Error updating room: " . $stmt->error;
    }
    $stmt->close();
}

// Delete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_room'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM rooms WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Room deleted successfully";
    } else {
        echo "Error deleting room: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการข้อมูลห้อง - ระบบจองโรงแรม</title>
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
        <h1>จัดการข้อมูลห้อง</h1>

        <form id="room-form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <h2>เพิ่ม/แก้ไขข้อมูลห้อง</h2>
            <input type="text" name="room_number" placeholder="หมายเลขห้อง" required>
            <input type="text" name="room_type" placeholder="ประเภทห้อง" required>
            <input type="number" name="price" placeholder="ราคา" step="0.01" required>
            <button type="submit" name="add_room">เพิ่มห้อง</button>
        </form>

        <table id="room-table">
            <thead>
                <tr>
                    <th>หมายเลขห้อง</th>
                    <th>ประเภทห้อง</th>
                    <th>ราคา</th>
                    <th>สถานะ</th>
                    <th>การดำเนินการ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["room_number"] . "</td>";
                        echo "<td>" . $row["room_type"] . "</td>";
                        echo "<td>" . $row["price"] . "</td>";
                        echo "<td>" . $row["status"] . "</td>";
                        echo "<td class='action-buttons'>
                                <form method='post' action='" . $_SERVER['PHP_SELF'] . "'>
                                    <input type='hidden' name='id' value='" . $row["id"] . "'>
                                    <input type='text' name='room_number' value='" . $row["room_number"] . "' required>
                                    <input type='text' name='room_type' value='" . $row["room_type"] . "' required>
                                    <input type='number' name='price' value='" . $row["price"] . "' step='0.01' required>
                                    <select name='status'>
                                        <option value='available' " . ($row["status"] == 'available' ? 'selected' : '') . ">ว่าง</option>
                                        <option value='occupied' " . ($row["status"] == 'occupied' ? 'selected' : '') . ">ไม่ว่าง</option>
                                        <option value='maintenance' " . ($row["status"] == 'maintenance' ? 'selected' : '') . ">ปิดปรับปรุง</option>
                                    </select>
                                    <button class='edit-btn' type='submit' name='update_room'>แก้ไข</button>
                                    <br>
                                    <button class='delete-btn' type='submit' name='delete_room' onclick='return confirm(\"คุณแน่ใจหรือไม่ที่จะลบห้องนี้?\");'>ลบ</button>
                                </form>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>ไม่พบข้อมูลห้อง</td></tr>";
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