<?php
require '../php/classes/database.classes.php';

$database = new Database;

$query = "UPDATE `employee` SET `status`= 'Offline' WHERE `empID` = :username";
$stmt = $database->connect()->prepare($query);
$stmt->bindParam(':username', $_POST['EMPID']);
$stmt->execute();

die();
