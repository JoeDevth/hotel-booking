<?php
// การเชื่อมต่อกับฐานข้อมูล
include 'db_connect.php';
$result = mysqli_query($conn, "SELECT * FROM rooms");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Rooms Management</title>
</head>
<body>
    <div class="container">
        <h2>Rooms Management</h2>
        <a href="add_room.php" class="btn">Add Room</a>
        <table>
            <thead>
                <tr>
                    <th>Room Number</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['room_number']; ?></td>
                        <td><?= $row['room_type']; ?></td>
                        <td><?= $row['price']; ?></td>
                        <td><?= $row['status']; ?></td>
                        <td>
                            <a href="edit_room.php?id=<?= $row['room_id']; ?>">Edit</a>
                            <a href="delete_room.php?id=<?= $row['room_id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
