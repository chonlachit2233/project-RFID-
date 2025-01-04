<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "DataBridge");

if (isset($_GET['rfid'])) {
    $rfid = $conn->real_escape_string($_GET['rfid']);

    // ตรวจสอบข้อมูลผู้ใช้
    $result = $conn->query("SELECT name, status FROM users WHERE rfid = '$rfid'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['status'] == 1) {
            echo json_encode(["success" => true, "name" => $row['name']]);
        } else {
            echo json_encode(["success" => false, "message" => "สถานะไม่ถูกต้อง"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "ไม่พบข้อมูลในระบบ"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "ไม่มีข้อมูล RFID"]);
}
?>
