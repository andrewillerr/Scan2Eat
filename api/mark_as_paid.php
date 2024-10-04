<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

header('Content-Type: application/json');
require_once 'db_config.php';  // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าได้รับข้อมูลจากการเรียก API หรือไม่
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['order_id']) && isset($data['status'])) {
    $order_id = $data['order_id'];
    $status = $data['status'];

    // ตรวจสอบให้แน่ใจว่า status คือ "paid" ก่อนที่จะอัปเดต
    if ($status === "paid") {
        // สร้างคำสั่ง SQL เพื่ออัปเดตสถานะคำสั่งซื้อ
        $sql = "UPDATE orders SET status = ? WHERE order_id = ? AND status = 'pending'";

        // เตรียมคำสั่ง SQL
        if ($stmt = $conn->prepare($sql)) {
            // ผูกค่าตัวแปร
            $stmt->bind_param("si", $status, $order_id);

            // ทำการ execute
            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "อัปเดตสถานะคำสั่งซื้อเรียบร้อย"]);
            } else {
                // ถ้า execute ไม่สำเร็จ ให้แสดงข้อความข้อผิดพลาดจากฐานข้อมูล
                echo json_encode(["success" => false, "message" => "ไม่สามารถอัปเดตสถานะได้: " . $stmt->error]);
            }

            // ปิด statement
            $stmt->close();
        } else {
            echo json_encode(["success" => false, "message" => "คำสั่ง SQL ไม่ถูกต้อง"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "สถานะไม่ถูกต้อง"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "ข้อมูลไม่ครบถ้วน"]);
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
