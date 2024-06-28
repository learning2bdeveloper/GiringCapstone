<?php
require '../php/classes/database.classes.php';

$selectedItem = $_POST['selectedItem']; // Received from Android

$query = "SELECT `item_no`, `item_description`, `price`, `quantity` FROM `inventory` WHERE `Owner_admin_name_FK` = :boss AND `item_name` = :selectedItem";

$database = new Database;

$stmt = $database->connect()->prepare($query);
$stmt->bindParam(':boss', $_POST['boss']);
$stmt->bindParam(':selectedItem', $selectedItem);
$stmt->execute();

$datas = array();

while ($row = $stmt->fetch()) {
    $datas[] = array(
        "itemNumber" => $row['item_no'],
        "itemDescription" => $row['item_description'],
        "itemPrice" => $row['price'],
        "itemOrigQuantity" => $row['quantity'],
    );
}

echo json_encode($datas);
exit();
