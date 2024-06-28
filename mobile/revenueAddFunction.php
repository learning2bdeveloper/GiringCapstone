<?php

require '../php/classes/database.classes.php';

// $errors['response'] = "Can't exceed Quantity";
// echo json_encode($errors);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $boss = $_POST['boss'];
    $empID = $_POST['empID'];
    $androiItemNo = $_POST['androidItemNo'];
    $androidItemName = $_POST['androidItemName'];
    $androidItemDescription = $_POST['androidItemDescription'];
    $androidItemPrice = (int) $_POST['androidItemPrice'];
    $androidItemQuantity = (int) $_POST['androidItemQuantity'];
    $androidItemAmount = (int) $_POST['androidItemAmount'];
    $androidItemTaxRate = (int) $_POST['androidItemTaxRate'];
    $androidItemTax = floatval($_POST['androidItemTax']);
    $androidItemTotal = floatval($_POST['androidItemTotal']);
    $androidItemOrigQuantity = (int) $_POST['androidItemOrigQuantity'];
    $currentDate = date("Y-m-d");

    $Database = new Database;

    $errors = array();
    if ($androidItemOrigQuantity < $androidItemQuantity) {
        $errors[] = array(
            "response" => "Can't exceed Quantity!",
        );
        echo json_encode($errors);
        die();
    }

    if ($androidItemOrigQuantity <= 10) {
        $errors[] = array(
            "response" => "Low Quantity!",
        );
        echo json_encode($errors);
        die();
    }

    # deduction of quantity
    $query1 = "UPDATE `inventory` SET `quantity` = :quantity WHERE `Owner_admin_name_FK`= :boss AND `item_no`= :itemNo;";

    $deductedQuantity = ($androidItemOrigQuantity - $androidItemQuantity);

    $stmt1 = $Database->connect()->prepare($query1);
    $stmt1->bindParam('boss', $boss);
    $stmt1->bindParam('itemNo', $androiItemNo);
    $stmt1->bindParam('quantity', $deductedQuantity);
    $stmt1->execute();

    #create Stock Control
    $query2 = "INSERT INTO `stockcontrol`(`empID_FK`, `Owner_admin_name_FK`, `date`, `item_no`, `item_name`, `item_description`, `price`, `quantity`, `amount`, `tax_rate`, `tax`, `total`) VALUES (:empID_FK, :boss, :currentDate, :item_no, :item_name, :item_description, :price, :quantity, :amount, :tax_rate, :tax, :total)";

    $stmt2 = $Database->connect()->prepare($query2);
    $stmt2->bindParam('empID_FK', $empID);
    $stmt2->bindParam('boss', $boss);
    $stmt2->bindParam('currentDate', $currentDate);
    $stmt2->bindParam('item_no', $androiItemNo);
    $stmt2->bindParam('item_name', $androidItemName);
    $stmt2->bindParam('item_description', $androidItemDescription);
    $stmt2->bindParam('price', $androidItemPrice);
    $stmt2->bindParam('quantity', $androidItemQuantity);
    $stmt2->bindParam('amount', $androidItemAmount);
    $stmt2->bindParam('tax_rate', $androidItemTaxRate);
    $stmt2->bindParam('tax', $androidItemTax);
    $stmt2->bindParam('total', $androidItemTotal);
    $stmt2->execute();

    // if (empty($androidItemNo) or empty($androidItemName) or empty($androidItemDescription) or empty($androidItemPrice) or empty($androidItemQuantity) or empty($androidItemAmount) or empty($androidItemTaxRate) or empty($androidItemTax) or empty($androidItemTotal)) {
    //     $errors[] = array(
    //         'response' => "Missing Inputs!",
    //     );
    //     echo json_encode($errors);
    // }

    $errors[] = array(
        "response" => "Success!",
    );
    echo json_encode($errors);
    die();
}
