<?php
include 'db_config.php'; // Database connection

$table_number = $_POST['table_number'];

$query = "UPDATE orders SET status='paid' WHERE table_number='$table_number' AND status='pending'";
if (mysqli_query($conn, $query)) {
    $table_status_query = "UPDATE tables SET status='available' WHERE table_number='$table_number'";
    mysqli_query($conn, $table_status_query);
    echo "Payment processed successfully";
} else {
    echo "Error processing payment: " . mysqli_error($conn);
}
?>
