<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

// เชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scan2eat";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลจากฐานข้อมูล
$sql = "SELECT tables.table_number, orders.menu_items, orders.payment_status 
        FROM tables 
        LEFT JOIN orders ON tables.table_number = orders.table_number 
        WHERE tables.status = 'not available'";  // แสดงเฉพาะโต๊ะที่ไม่ว่าง

$result = $conn->query($sql);

$orders = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $orders[] = [
            'table_number' => $row['table_number'],
            'menu_items' => json_decode($row['menu_items']),
            'payment_status' => $row['payment_status']
        ];
    }
} else {
    echo json_encode(['message' => 'No orders found']);
}

$conn->close();

echo json_encode($orders);
?>
