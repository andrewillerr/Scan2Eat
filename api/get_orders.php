<?php
// fetch_orders.php - ดึงข้อมูลคำสั่งอาหารของโต๊ะ

include 'db_config.php';

// รับค่า table_number ผ่าน query string
$table_number = isset($_GET['table_number']) ? intval($_GET['table_number']) : 0;

if ($table_number == 0) {
    echo json_encode(["message" => "Invalid table number"]);
    exit();
}

// ใช้ prepared statement เพื่อป้องกัน SQL Injection
$sql = "SELECT * FROM orders WHERE table_number = ?";
$stmt = $conn->prepare($sql);

// Bind ค่า table_number เป็นตัวแปรที่ใช้ใน SQL query
$stmt->bind_param("i", $table_number);

// ทำการ execute คำสั่ง SQL
$stmt->execute();

// ดึงผลลัพธ์
$result = $stmt->get_result();

$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // เพิ่มรายการอาหารในคำสั่งถ้ามี
        $orderId = $row['order_id'];
        $itemsSql = "SELECT item_name FROM ordered_items INNER JOIN menu ON ordered_items.item_id = menu.item_id WHERE order_id = ?";
        $itemsStmt = $conn->prepare($itemsSql);
        $itemsStmt->bind_param("i", $orderId);
        $itemsStmt->execute();
        $itemsResult = $itemsStmt->get_result();

        $items = [];
        while ($itemRow = $itemsResult->fetch_assoc()) {
            $items[] = $itemRow['item_name'];
        }
        $row['items'] = $items; // เพิ่มรายการอาหารในคำสั่งซื้อ
        $orders[] = $row;
    }

    echo json_encode($orders); // ส่งข้อมูลคำสั่งซื้อ
} else {
    echo json_encode(["message" => "No orders found for this table"]);
}

// ปิดการเชื่อมต่อฐานข้อมูล
$stmt->close();
$conn->close();
?>
