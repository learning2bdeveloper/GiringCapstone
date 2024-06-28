<?php

require '../php/classes/database.classes.php';

// $errors['response'] = "Can't exceed Quantity";
// echo json_encode($errors);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $boss = $_POST['boss'];
    $empID = $_POST['empID'];
    $androidCategory = $_POST['androidCategory'];
    $androidBudget = $_POST['androidBudget'];
    $androidRemark = $_POST['androidRemark'];
    $androidAmount = $_POST['androidAmount'];
    $currentDate = date("Y-m-d");

    $Database = new Database;

    $errors = array();
    if (empty(trim($androidRemark))) {
        $errors[] = array(
            "response" => "Description are all spaces!",
        );
        echo json_encode($errors);
        die();
    }

    if (!preg_match("/^[[a-zA-Z0-9 !?.]*$/", $androidRemark)) {
        $errors[] = array(
            "response" => "Enter valid Remarks!",
        );
        echo json_encode($errors);
        die();
    }

    if ($androidBudget < $androidAmount) {
        $errors[] = array(
            "response" => "Overbudget",
        );
        echo json_encode($errors);
        die();
    }

    $query1 = "INSERT INTO `active_expense`(`Owner_admin_name_FK`, `empID_FK`, `Date`, `Category`, `Category_amount`, `Description`, `Amount`) VALUES (:boss, :empID_FK, :currentDate, :category, :Category_amount, :descriptions, :amount)";
    $stmt1 = $Database->connect()->prepare($query1);
    $stmt1->bindParam(':boss', $boss);
    $stmt1->bindParam(':empID_FK', $empID);
    $stmt1->bindParam(':currentDate', $currentDate);
    $stmt1->bindParam('category', $androidCategory);
    $stmt1->bindParam('Category_amount', $androidBudget);
    $stmt1->bindParam('descriptions', $androidRemark);
    $stmt1->bindParam('amount', $androidAmount);
    $stmt1->execute();
    $stmt1 = null;

    $newAmount = $androidBudget - $androidAmount;
    $query2 = "UPDATE `budget` SET `Amount`= :amount, `Update_date`= :updateDate WHERE `Category` = :Category AND `Owner_admin_name_FK` = :Owner_admin_name_FK";
    $stmt2 = $Database->connect()->prepare($query2);
    $stmt2->bindParam('Owner_admin_name_FK', $boss);
    $stmt2->bindParam('Category', $androidCategory);
    $stmt2->bindParam(':updateDate', $currentDate);
    $stmt2->bindParam('amount', $newAmount);
    $stmt2->execute();
    $stmt2 = null;

    $errors[] = array(
        "response" => "Success!",
    );
    echo json_encode($errors);
    die();
    // # deduction of quantity
    // $query1 = "UPDATE `inventory` SET `quantity` = :quantity WHERE `Owner_admin_name_FK`= :boss AND `item_no`= :itemNo;";

    // $deductedQuantity = ($androidItemOrigQuantity - $androidItemQuantity);

    // $stmt1 = $Database->connect()->prepare($query1);
    // $stmt1->bindParam('boss', $boss);
    // $stmt1->bindParam('itemNo', $androiItemNo);
    // $stmt1->bindParam('quantity', $deductedQuantity);
    // $stmt1->execute();

    // #create Stock Control
    // $query2 = "INSERT INTO `stockcontrol`(`empID_FK`, `Owner_admin_name_FK`, `date`, `item_no`, `item_name`, `item_description`, `price`, `quantity`, `amount`, `tax_rate`, `tax`, `total`) VALUES (:empID_FK, :boss, :currentDate, :item_no, :item_name, :item_description, :price, :quantity, :amount, :tax_rate, :tax, :total)";

    // $stmt2 = $Database->connect()->prepare($query2);
    // $stmt2->bindParam('empID_FK', $empID);
    // $stmt2->bindParam('boss', $boss);
    // $stmt2->bindParam('currentDate', $currentDate);
    // $stmt2->bindParam('item_no', $androiItemNo);
    // $stmt2->bindParam('item_name', $androidItemName);
    // $stmt2->bindParam('item_description', $androidItemDescription);
    // $stmt2->bindParam('price', $androidItemPrice);
    // $stmt2->bindParam('quantity', $androidItemQuantity);
    // $stmt2->bindParam('amount', $androidItemAmount);
    // $stmt2->bindParam('tax_rate', $androidItemTaxRate);
    // $stmt2->bindParam('tax', $androidItemTax);
    // $stmt2->bindParam('total', $androidItemTotal);
    // $stmt2->execute();

    // // if (empty($androidItemNo) or empty($androidItemName) or empty($androidItemDescription) or empty($androidItemPrice) or empty($androidItemQuantity) or empty($androidItemAmount) or empty($androidItemTaxRate) or empty($androidItemTax) or empty($androidItemTotal)) {
    // //     $errors[] = array(
    // //         'response' => "Missing Inputs!",
    // //     );
    // //     echo json_encode($errors);
    // // }

    // $errors[] = array(
    //     "response" => "Success!",
    // );
    // echo json_encode($errors);
    // die();
}
