<?php
session_start();

#initializes and requires tanan na files needed here para no need mag initialize liwat when may bag o na file
require '../classes/database.classes.php';
require '../classes/stockcontrol.classes.php';
require '../controller/stockcontrol.controller.php';

if (isset($_POST['createstockcontrol'])) {

    $itemNo = $_POST['itemNo'];
    $itemName = $_POST["itemName"];
    $itemDescription = $_POST["itemDescription"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $amount = $_POST["amount"];
    $tax_rate = $_POST["tax_rate"];
    $tax = $_POST["tax"];
    $total = $_POST["total"];
    //put the data to the controller for error_handling

    $stockcontrolController = new stockcontrolController;
    $stockcontrolController->set_add_stockcontrol_data($itemNo, $itemName, $itemDescription, $price, $quantity, $amount, $tax_rate, $tax, $total);
    $stockcontrolController->insert_add_stockcontrol_data();

    header("Location: ../views/stockcontrol.view/stockcontrol.php?added=succes");
}

if (isset($_POST['deletestockcontrol'])) {

    $id = $_POST['id'];
    $itemNo = $_POST['itemNo'];
    $quantity = $_POST['quantity'];

    $stockcontrolController = new stockcontrolController;
    $stockcontrolController->set_delete_stockcontrol_data($id, $itemNo, $quantity);
    $stockcontrolController->delete_stockcontrol();

    echo "stockcontrol.php";
}

if (isset($_POST['update'])) {

    $id = $_POST['Update'];
    $description = $_POST['description'];
    $categoryAmount = $_POST['categoryAmount'];

    $ExpenseController = new ExpenseController;
    $ExpenseController->set_update_expense_data($id, $description, $categoryAmount);
    $ExpenseController->insert_update_expense_data();
}
