<?php
include 'db_config.php'; // Database connection

$table_number = $_POST['table_number'];
$status = $_POST['status'];

$query = "UPDATE tables SET status='$status' WHERE table_number='$table_number'";
if (mysqli_query($conn, $query)) {
    echo "Table status updated successfully";
} else {
    echo "Error updating table status: " . mysqli_error($conn);
}
?>
