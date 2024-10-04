<?php
// get_unpaid_orders.php - ดึงข้อมูลการสั่งซื้อที่ยังไม่ชำระเงิน พร้อมรายการอาหาร
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
include 'db_config.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลการสั่งซื้อที่ยังไม่ชำระเงิน พร้อมรายการอาหาร
$sql = "
    SELECT o.order_id, o.total_price, o.order_date, o.table_number, o.status, 
           i.item_id, i.quantity, m.item_name
    FROM orders o
    JOIN ordered_items i ON o.order_id = i.order_id
    JOIN menu m ON i.item_id = m.item_id
    WHERE o.status = 'pending'
";

$result = $conn->query($sql);

if ($result === false) {
    die("Error executing query: " . $conn->error);  // หาก query ล้มเหลว
}

if ($result->num_rows > 0) {
    // สร้าง array เพื่อเก็บข้อมูล
    $unpaidOrders = array();

    // ดึงข้อมูลการสั่งซื้อที่ยังไม่ชำระเงิน
    while ($row = $result->fetch_assoc()) {
        // ตรวจสอบว่า order_id นี้ยังไม่มีใน array หรือไม่
        if (!isset($unpaidOrders[$row['order_id']])) {
            $unpaidOrders[$row['order_id']] = array(
                'order_id' => $row['order_id'],
                'total_price' => $row['total_price'],
                'order_date' => $row['order_date'],
                'table_number' => $row['table_number'],
                'status' => $row['status'],
                'items' => array() // เริ่มต้นเป็น array ว่าง
            );
        }
        
        // เพิ่มรายการอาหารเข้าไปใน array ของ order_id ที่เกี่ยวข้อง
        $unpaidOrders[$row['order_id']]['items'][] = array(
            'item_id' => $row['item_id'],
            'item_name' => $row['item_name'],
            'quantity' => $row['quantity']
        );
    }

    // ส่งข้อมูลกลับเป็น JSON
    echo json_encode(array_values($unpaidOrders));
} else {
    echo "[]";  // หากไม่พบข้อมูล
}

// ปิดการเชื่อมต่อ
$conn->close();
?>
