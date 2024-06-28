<?php
session_start();
require '../../classes/database.classes.php';
require '../../classes/inventory.classes.php';
$postedData = json_decode(file_get_contents('php://input'), true);

$data = $postedData['item_no'];

$Inventory = new Inventory;
$outputs = $Inventory->get_item_name_and_item_description($_SESSION['admin_name'], $data);

$array = array();
foreach ($outputs as $output) {
    $array = [
        "item_name" => $output['item_name'],
        "item_description" => $output['item_description'],
        "price" => $output['price'],
        "quantity" => $output['quantity'],
    ];
}

echo json_encode($array);
