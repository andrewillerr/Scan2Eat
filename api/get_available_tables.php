<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root"; // ชื่อผู้ใช้ฐานข้อมูล
$password = ""; // รหัสผ่านฐานข้อมูล
$dbname = "scan2eat"; // ชื่อฐานข้อมูล

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// สร้างคำสั่ง SQL เพื่อดึงข้อมูลโต๊ะที่ว่าง
$sql = "SELECT table_number, status FROM tables WHERE status = 'available'";
$result = $conn->query($sql);

$tables = [];

if ($result->num_rows > 0) {
    // ดึงข้อมูลแถวต่อแถว
    while($row = $result->fetch_assoc()) {
        $tables[] = $row;
    }
} else {
    echo "0 results";
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();

// ส่งข้อมูลเป็น JSON
header('Content-Type: application/json');
echo json_encode($tables);
?>
