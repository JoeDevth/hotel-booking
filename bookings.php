<?php
require_once 'config.php';

// Create
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_booking'])) {
    $room_id = $_POST['room_id'];
    $customer_id = $_POST['customer_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $total_price = $_POST['total_price'];

    $sql = "INSERT INTO bookings (room_id, customer_id, check_in, check_out, total_price) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissd", $room_id, $customer_id, $check_in, $check_out, $total_price);

    if ($stmt->execute()) {
        echo "New booking added successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Read
$sql = "SELECT b.*, r.room_number, c.name AS customer_name 
        FROM bookings b 
        JOIN rooms r ON b.room_id = r.id 
        JOIN customers c ON b.customer_id = c.id";
$result = $conn->query($sql);

// Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_booking'])) {
    $id = $_POST['id'];
    $room_id = $_POST['room_id'];
    $customer_id = $_POST['customer_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $total_price = $_POST['total_price'];
    $status = $_POST['status'];

    $sql = "UPDATE bookings SET room_id=?, customer_id=?, check_in=?, check_out=?, total_price=?, status=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissdsi", $room_id, $customer_id, $check_in, $check_out, $total_price, $status, $id);

    if ($stmt->execute()) {
        echo "Booking updated successfully";
    } else {
        echo "Error updating booking: " . $stmt->error;
    }
    $stmt->close();
}

// Delete
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_booking'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM bookings WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Booking deleted successfully";
    } else {
        echo "Error deleting booking: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch rooms for dropdown
$rooms_sql = "SELECT id, room_number FROM rooms";
$rooms_result = $conn->query($rooms_sql);

// Fetch customers for dropdown
$customers_sql = "SELECT id, name FROM customers";
$customers_result = $conn->query($customers_sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการการจอง - ระบบจองโรงแรม</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index_login.php">หน้าแรก</a></li>
                <li><a href="rooms.php">ข้อมูลห้อง</a></li>
                <li><a href="employees.php">พนักงาน</a></li>
                <li><a href="customers.php">ลูกค้า</a></li>
                <li><a href="bookings.php">การจอง</a></li>
                <li><a href="login.php">ออกจากระบบ</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>จัดการการจอง</h1>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <h2>เพิ่ม/แก้ไขการจอง</h2>
            <select name="room_id" required>
                <option value="">เลือกห้อง</option>
                <?php
                while ($room = $rooms_result->fetch_assoc()) {
                    echo "<option value='" . $room['id'] . "'>" . $room['room_number'] . "</option>";
                }
                ?>
            </select>
            <select name="customer_id" required>
                <option value="">เลือกลูกค้า</option>
                <?php
                while ($customer = $customers_result->fetch_assoc()) {
                    echo "<option value='" . $customer['id'] . "'>" . $customer['name'] . "</option>";
                }
                ?>
            </select>
            <input type="date" name="check_in" placeholder="วันที่เช็คอิน" required>
            <input type="date" name="check_out" placeholder="วันที่เช็คเอาท์" required>
          
            <input type="number" name="total_price" placeholder="ราคารวม" step="0.01" required>
            <button type="submit" name="add_booking">เพิ่มการจอง</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>หมายเลขห้อง</th>
                    <th>ชื่อลูกค้า</th>
                    <th>วันที่เช็คอิน</th>
                    <th>วันที่เช็คเอาท์</th>
                    <th>ราคารวม</th>
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
                        echo "<td>" . $row["customer_name"] . "</td>";
                        echo "<td>" . $row["check_in"] . "</td>";
                        echo "<td>" . $row["check_out"] . "</td>";
                        echo "<td>" . $row["total_price"] . "</td>";
                        echo "<td>" . $row["status"] . "</td>";
                        echo "<td>
                                <form method='post' action='" . $_SERVER['PHP_SELF'] . "'>
                                    <input type='hidden' name='id' value='" . $row["id"] . "'>
                                    <select name='room_id' required>";
                                    $rooms_result->data_seek(0);
                                    while ($room = $rooms_result->fetch_assoc()) {
                                        $selected = ($room['id'] == $row['room_id']) ? 'selected' : '';
                                        echo "<option value='" . $room['id'] . "' $selected>" . $room['room_number'] . "</option>";
                                    }
                        echo "      </select>
                                    <select name='customer_id' required>";
                                    $customers_result->data_seek(0);
                                    while ($customer = $customers_result->fetch_assoc()) {
                                        $selected = ($customer['id'] == $row['customer_id']) ? 'selected' : '';
                                        echo "<option value='" . $customer['id'] . "' $selected>" . $customer['name'] . "</option>";
                                    }
                        echo "      </select>
                                    <input type='date' name='check_in' value='" . $row["check_in"] . "' required>
                                    <input type='date' name='check_out' value='" . $row["check_out"] . "' required>
                                    <input type='number' name='total_price' value='" . $row["total_price"] . "' step='0.01' required>
                                    <select name='status'>
                                        <option value='confirmed' " . ($row["status"] == 'confirmed' ? 'selected' : '') . ">ยืนยันแล้ว</option>
                                        <option value='cancelled' " . ($row["status"] == 'cancelled' ? 'selected' : '') . ">ยกเลิก</option>
                                        <option value='completed' " . ($row["status"] == 'completed' ? 'selected' : '') . ">เสร็จสิ้น</option>
                                    </select>
                                    <button type='submit' name='update_booking'>แก้ไข</button>
                                   <br>   <button type='submit' name='delete_booking' onclick='return confirm(\"คุณแน่ใจหรือไม่ที่จะลบการจองนี้?\");'>ลบ</button>
                                </form>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>ไม่พบข้อมูลการจอง</td></tr>";
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