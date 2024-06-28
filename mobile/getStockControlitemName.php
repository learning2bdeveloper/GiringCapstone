<?php

require '../php/classes/database.classes.php';

$datas = array();

$query = "SELECT `item_name` FROM `inventory` WHERE `Owner_admin_name_FK` = :boss";

$database = new Database;

$stmt = $database->connect()->prepare($query);
$stmt->bindParam(':boss', $_POST['boss']);
$stmt->execute();

while ($row = $stmt->fetch()) {
    $datas[] = array(
        "itemName" => $row['item_name'],
    );
}

echo json_encode($datas);

exit();
