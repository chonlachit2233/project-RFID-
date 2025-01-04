<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "DataBridge");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rfid = $_POST['rfid'];
    $activity = $_POST['activity'];

    // ตรวจสอบ RFID และสถานะอีกครั้งก่อนเช็คอิน
    $result = $conn->query("SELECT id, status FROM users WHERE rfid = '$rfid'");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['status'] == 1) {
            $user_id = $row['id'];

            // ตรวจสอบว่าผู้ใช้เคยเช็คอินในกิจกรรมนี้แล้วหรือไม่
            $check_duplicate = $conn->query("SELECT * FROM checkin WHERE user_id = $user_id AND activity = '$activity'");
            if ($check_duplicate->num_rows > 0) {
                echo json_encode(["success" => false, "message" => "คุณเคยเช็คอินในกิจกรรมนี้แล้ว"]);
            } else {
                // เพิ่มข้อมูลการเช็คอินใหม่
                $stmt = $conn->prepare("INSERT INTO checkin (user_id, activity, checkin_time) VALUES (?, ?, NOW())");
                $stmt->bind_param("is", $user_id, $activity);
                $stmt->execute();

                echo json_encode(["success" => true, "message" => "เช็คอินสำเร็จสำหรับกิจกรรม: $activity"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "สถานะผู้ใช้ไม่ถูกต้อง"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "ไม่พบข้อมูล RFID"]);
    }
}
?>
