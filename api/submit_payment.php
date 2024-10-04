<?php
// submit_payment.php

header("Content-Type: application/json");

include_once 'db_connection.php';

// รับข้อมูลที่ส่งมาจากแอป
$order_id = $_POST['order_id'];
$amount = $_POST['amount'];

// 1. เพิ่มข้อมูลการชำระเงินในตาราง payments
$query = "INSERT INTO payments (order_id, amount, status) VALUES (?, ?, 'completed')";
$stmt = $conn->prepare($query);
$stmt->bind_param("id", $order_id, $amount);
$stmt->execute();

// 2. อัพเดทสถานะของคำสั่งซื้อในตาราง orders เป็น 'paid'
$query_order = "UPDATE orders SET status = 'paid' WHERE order_id = ?";
$stmt_order = $conn->prepare($query_order);
$stmt_order->bind_param("i", $order_id);
$stmt_order->execute();

// ส่งผลลัพธ์กลับไปที่แอป
if ($stmt_order->affected_rows > 0) {
    echo json_encode(['success' => true, 'message' => 'Payment successful']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to complete payment']);
}
?>
