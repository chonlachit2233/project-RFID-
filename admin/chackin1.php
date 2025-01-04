<?php
header('Content-Type: text/html; charset=utf-8');
$conn = new mysqli("localhost", "root", "", "DataBridge");

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

// ลบข้อมูลเมื่อมีคำขอ GET ที่มีค่า `delete_id`
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM checkin WHERE id = $delete_id");
    header("Location: " . $_SERVER['PHP_SELF']); // รีเฟรชหน้าเพื่ออัปเดตข้อมูล
    exit;
}

// ดึงข้อมูลการเช็คอิน
$result = $conn->query("
    SELECT 
        checkin.id,
        users.name AS user_name,
        checkin.activity,
        checkin.checkin_time
    FROM 
        checkin 
    JOIN 
        users ON checkin.user_id = users.id
    ORDER BY 
        checkin.checkin_time DESC
");
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการเช็คอิน</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
            border: 1px solid #ddd;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table th {
            background: #2196F3;
            color: white;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .delete-btn {
            background: #FF5252;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background: #D32F2F;
        }
    </style>
</head>
<body>
    <h1>รายการเช็คอิน</h1>
    <table>
        <thead>
            <tr>
                <th>ชื่อผู้ใช้</th>
                <th>กิจกรรม</th>
                <th>เวลาเช็คอิน</th>
                <th>ลบ</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['user_name']) ?></td>
                        <td><?= htmlspecialchars($row['activity']) ?></td>
                        <td><?= htmlspecialchars($row['checkin_time']) ?></td>
                        <td>
                            <form method="get" style="margin: 0;">
                                <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                                <button type="submit" class="delete-btn" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?')">ลบ</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center;">ไม่มีข้อมูลการเช็คอิน</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
