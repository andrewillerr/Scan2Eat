<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require_once 'db_config.php';  // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าได้รับข้อมูลจากการเรียก API หรือไม่
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['date'])) {
    $date = $data['date']; // รับวันที่จาก API
    $date = $conn->real_escape_string($date); // ป้องกัน SQL Injection

    // คำสั่ง SQL เพื่อดึงข้อมูลคำสั่งซื้อที่ตรงกับวันที่
    $sql = "SELECT o.order_id, o.table_number, o.total_price, o.order_date, o.status, 
                   i.item_name AS menu_item, oi.quantity 
            FROM orders o
            JOIN order_items oi ON o.order_id = oi.order_id
            JOIN menu i ON oi.item_id = i.item_id
            WHERE DATE(o.order_date) = ?"; // ใช้ DATE() เพื่อดึงข้อมูลตามวัน

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $date); // ผูกค่าตัวแปร
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $orders = [];
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row;
            }
            echo json_encode($orders); // ส่งข้อมูลเป็น JSON
        } else {
            echo json_encode(["success" => false, "message" => "ไม่พบคำสั่งซื้อในวันที่เลือก"]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "คำสั่ง SQL ไม่ถูกต้อง"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "ไม่พบวันที่ที่ต้องการ"]);
}

$conn->close();
?>
