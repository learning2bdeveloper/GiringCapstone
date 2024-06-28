<?php
session_start();
//includes the other files
require '../classes/database.classes.php';
require '../classes/sales.classes.php';
require '../controller/sales.controller.php';
require '../functions/salesIDAutoGenerator.php';

//dapat int ang good receipt number & quantity & amount
if (isset($_POST["salesAdd"])) {

    try {

        $itemName = ucwords($_POST['itemName']);
        $itemDescription = strtoupper($_POST['itemDescription']);
        $quantity = $_POST['quantity'];
        $amount = $_POST['amount'];
        $total = $_POST['total'];

        $salesController = new salesController;

        $salesController->setsalesAddData($itemName, $itemDescription, $quantity, $amount, $total);
        $salesController->addsales();

    } catch (Exception $e) {
        echo 'Error Message: ' . $e;
    }

    header('Location: ../views/sales.view/sales.php?salesAdded');

}

if (isset($_POST['Delete'])) {
    $id = $_POST['Delete'];

    $salesController = new salesController;

    $salesController->setsalesDeleteData($id);
    $salesController->delete_sales();
    // $sales->redoDeductionsalesBudget($salesID, $currentAdmin, $currentAmount);
    // $sales->detele_id_sales($salesID, $currentAdmin);
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

    $salesController = new salesController;
    $salesController->setsalesUpdateData($currentItemNo, $currentItemName, $id, $itemNo, $itemName, $itemDescription, $quantity, $amount);
    $salesController->update_sales();

    echo 'sales.php'; // para sa js fetch e return ang url haha
}
