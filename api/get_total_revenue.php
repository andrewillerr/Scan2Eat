<?php
// get_total_revenue.php - ดึงข้อมูลรายรับทั้งหมด
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
include 'db_config.php';

// เขียนคำสั่ง SQL เพื่อดึงข้อมูลรายรับทั้งหมดจากฐานข้อมูล
$sql = "SELECT SUM(total_price) AS total FROM orders WHERE status='paid'";  // ใช้ total_price แทน amount

// รันคำสั่ง SQL
$result = $conn->query($sql);

// ตรวจสอบว่า query สำเร็จหรือไม่
if ($result === false) {
    // แสดงข้อผิดพลาดถ้าเกิดปัญหากับคำสั่ง SQL
    die(json_encode(["message" => "SQL error: " . $conn->error]));
}

// ตรวจสอบว่ามีผลลัพธ์หรือไม่
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(["total" => $row['total']]);
} else {
    echo json_encode(["total" => 0]);
}

$conn->close();
?>
