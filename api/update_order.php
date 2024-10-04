<?php
// update_order.php - อัพเดทคำสั่งอาหาร

include 'db_config.php';

$data = json_decode(file_get_contents("php://input"), true);

$order_id = $data['order_id'];
$quantity = $data['quantity'];

// อัพเดทจำนวนคำสั่งอาหาร
$sql = "UPDATE orders SET quantity='$quantity' WHERE id='$order_id'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["message" => "Order updated successfully"]);
} else {
    echo json_encode(["message" => "Error updating order: " . $conn->error]);
}

$conn->close();
?>
