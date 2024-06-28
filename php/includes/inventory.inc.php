<?php
session_start();
//includes the other files
require '../classes/database.classes.php';
require '../classes/inventory.classes.php';
require '../controller/inventory.controller.php';
require '../functions/itemNOAutoGenerator.php';
//dapat int ang good receipt number & quantity & amount
if (isset($_POST["inventoryAdd"])) {

    try {
        //arrays na sila
        //get data from inventoryAddForm.php
        $itemName = $_POST['itemName'];
        $itemDescription = $_POST['itemDescription'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];

        $inventoryController = new InventoryController;

        $inventoryController->setInventoryAddData($itemName, $itemDescription, $quantity, $price);
        $inventoryController->addInventory();

    } catch (Exception $e) {
        echo 'Error Message: ' . $e;
    }

    header('Location: ../views/inventory.view/inventory.php?InventoryAdded');

}

if (isset($_POST['Delete'])) {
    $id = $_POST['Delete'];
    $currentAmount = $_POST['currentAmount'];

    $inventoryController = new InventoryController;

    $inventoryController->setInventoryDeleteData($id, $currentAmount);
    $inventoryController->delete_inventory();
    // $Inventory->redoDeductionInventoryBudget($inventoryID, $currentAdmin, $currentAmount);
    // $Inventory->detele_id_inventory($inventoryID, $currentAdmin);
}

if (isset($_POST['Update'])) {
    $id = $_POST['Update'];
    $itemNo = strtoupper($_POST['itemNo']);
    $itemName = ucwords($_POST['itemName']);
    $itemDescription = ucfirst($_POST['itemDescription']);
    $currentItemNo = $_POST['currentItemNo'];
    $currentItemName = $_POST['curretItemName'];
    $quantity = $_POST['quantity'];
    $amount = $_POST['amount'];

    $inventoryController = new InventoryController;
    $inventoryController->setInventoryUpdateData($currentItemNo, $currentItemName, $id, $itemNo, $itemName, $itemDescription, $quantity, $amount);
    $inventoryController->update_inventory();

    echo 'inventory.php'; // para sa js fetch e return ang url haha
}
