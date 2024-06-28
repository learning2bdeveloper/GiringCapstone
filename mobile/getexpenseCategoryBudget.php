<?php

require '../php/classes/database.classes.php';

$selectedItem = $_POST['selectedItem']; // Received from Android

$datas = array();

$query = "SELECT `Amount` FROM `budget` WHERE `Owner_admin_name_FK` = :boss AND `Category` = :selectedItem";

$database = new Database;

$stmt = $database->connect()->prepare($query);
$stmt->bindParam(':boss', $_POST['boss']);
$stmt->bindParam(':selectedItem', $selectedItem);
$stmt->execute();

while ($row = $stmt->fetch()) {
    $datas[] = array(
        "selectedItem" => $row['Amount'],
    );
}

echo json_encode($datas);

exit();
