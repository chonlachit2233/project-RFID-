<?php
// เชื่อมต่อกับฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "DataBridge");

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ลบข้อมูลถ้ามีการร้องขอลบ
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM users WHERE id = $delete_id";
    if ($conn->query($delete_query) === TRUE) {
        echo "<script>alert('ลบข้อมูลสำเร็จ'); window.location.href='regismin.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการลบข้อมูล');</script>";
    }
}

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$query = "SELECT * FROM users";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แสดงข้อมูลการลงทะเบียน</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 20px;
        }
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .delete-button {
            background-color: #ff4444;
            color: white;
        }
        .delete-button:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>

    <h1>แสดงข้อมูลการลงทะเบียน</h1>

    <?php if ($result->num_rows > 0): ?>
        <!-- แสดงตารางถ้ามีข้อมูล -->
        <table>
            <thead>
                <tr>
                    <th>ชื่อ</th>
                    <th>RFID</th>
                    <th>สถานะ</th>
                    <th>วันที่ลงทะเบียน</th>
                    <th>ลบ</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['rfid']); ?></td>
                        <td><?php echo $row['status'] == 1 ? 'ยืนยันแล้ว' : 'ยังไม่ยืนยัน'; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td>
                            <!-- ปุ่มลบ -->
                            <a href="?delete_id=<?php echo $row['id']; ?>" class="delete-button" onclick="return confirm('คุณแน่ใจหรือไม่ว่าจะลบข้อมูลนี้?')">ลบ</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>ไม่มีข้อมูลผู้ใช้ในระบบ</p>
    <?php endif; ?>
</body>
</html>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
