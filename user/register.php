<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "DataBridge");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $rfid = $conn->real_escape_string($_POST['rfid']);
    $status = isset($_POST['status']) ? intval($_POST['status']) : 0;

    // ตรวจสอบว่ามีข้อมูลนี้ในฐานข้อมูลหรือไม่
    $result = $conn->query("SELECT id FROM users WHERE rfid = '$rfid'");
    if ($result->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "RFID นี้ถูกลงทะเบียนแล้ว"]);
    } else {
        // บันทึกข้อมูลลงฐานข้อมูล
        $stmt = $conn->prepare("INSERT INTO users (name, rfid, status) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $name, $rfid, $status);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "ลงทะเบียนสำเร็จ"]);
        } else {
            echo json_encode(["success" => false, "message" => "เกิดข้อผิดพลาดในการบันทึกข้อมูล"]);
        }
    }
} else {
    echo json_encode(["success" => false, "message" => "ไม่มีข้อมูลที่ส่งมา"]);
}
?>
