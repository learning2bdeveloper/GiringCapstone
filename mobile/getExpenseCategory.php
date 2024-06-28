<?php

require '../php/classes/database.classes.php';

$datas = array();

$query = "SELECT `Category` FROM `budget` WHERE `Owner_admin_name_FK` = :boss";

$database = new Database;

$stmt = $database->connect()->prepare($query);
$stmt->bindParam(':boss', $_POST['boss']);
$stmt->execute();

while ($row = $stmt->fetch()) {
    $datas[] = array(
        "category" => $row['Category'],
    );
}

echo json_encode($datas);

exit();
