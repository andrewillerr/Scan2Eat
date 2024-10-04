<?php
// fetch_menu.php - ดึงข้อมูลเมนูทั้งหมด

include 'db_config.php';

$sql = "SELECT * FROM menu";
$result = $conn->query($sql);

$menu_items = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $menu_items[] = $row;
    }
    echo json_encode($menu_items);
} else {
    echo json_encode(["message" => "No items found"]);
}

$conn->close();
?>
