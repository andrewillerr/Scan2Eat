<!-- <?php
include 'db_config.php'; // Database connection

$table_number = $_POST['table_number'];
$order_items = json_decode($_POST['order_items'], true);
$total_price = $_POST['total_price'];

mysqli_begin_transaction($conn);

try {
    $order_query = "INSERT INTO orders (table_number, total_price, status) VALUES ('$table_number', '$total_price', 'pending')";
    mysqli_query($conn, $order_query);
    $order_id = mysqli_insert_id($conn);

    foreach ($order_items as $item_id => $quantity) {
        $ordered_item_query = "INSERT INTO ordered_items (order_id, item_id, quantity) VALUES ('$order_id', '$item_id', '$quantity')";
        mysqli_query($conn, $ordered_item_query);
    }

    mysqli_commit($conn);
    echo "Order submitted successfully";
} catch (Exception $e) {
    mysqli_rollBack($conn);
    echo "Failed to submit order: " . $e->getMessage();
}
?> -->


<?php
header('Content-Type: application/json');
include 'db_config.php';
// การเชื่อมต่อฐานข้อมูล
// $conn = new mysqli('localhost', 'username', 'password', 'scan2eat');

// if ($conn->connect_error) {
//     echo json_encode(['success' => false, 'message' => 'Connection failed']);
//     exit();
// }

// รับข้อมูลจาก request
$tableNumber = $_POST['table_number'];
$orderItems = json_decode($_POST['order_items'], true);
$totalPrice = $_POST['total_price'];

// บันทึกคำสั่งซื้อในตาราง orders
$orderQuery = "INSERT INTO orders (table_number, total_price, status) VALUES (?, ?, 'paid')";
$stmt = $conn->prepare($orderQuery);
$stmt->bind_param('id', $tableNumber, $totalPrice);

if ($stmt->execute()) {
    $orderId = $stmt->insert_id;  // เก็บค่า order_id ของคำสั่งซื้อที่ถูกเพิ่ม

    // บันทึกรายการสั่งซื้อในตาราง order_items
    foreach ($orderItems as $itemId => $quantity) {
        $itemQuery = "INSERT INTO order_items (order_id, item_id, quantity) VALUES (?, ?, ?)";
        $itemStmt = $conn->prepare($itemQuery);
        $itemStmt->bind_param('iii', $orderId, $itemId, $quantity);
        $itemStmt->execute();
        $itemStmt->close();
    }

    // บันทึกการยืนยันการชำระเงินในตาราง confirmations
    $confirmedBy = "เจ้าของร้าน"; // หรือใช้ user_id ถ้ามีระบบล็อกอิน
    $confirmQuery = "INSERT INTO confirmations (order_id, table_number, confirmed_by) VALUES (?, ?, ?)";
    $confirmStmt = $conn->prepare($confirmQuery);
    $confirmStmt->bind_param('iis', $orderId, $tableNumber, $confirmedBy);
    
    if ($confirmStmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Order and confirmation recorded successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to record confirmation']);
    }
    
    $confirmStmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to place order']);
}

$stmt->close();
$conn->close();
?>

