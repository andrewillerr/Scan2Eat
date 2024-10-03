<?php
// db_config.php - ตั้งค่าการเชื่อมต่อฐานข้อมูล

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scan2eat";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
