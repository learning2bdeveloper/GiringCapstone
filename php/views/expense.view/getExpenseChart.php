<?php

require '../../classes/database.classes.php';

$postedData = json_decode(file_get_contents('php://input'), true);
$Database = new Database();

$query2 = "SELECT MONTH(`Date`) AS 'month', SUM(`Amount`) AS total_expense FROM `active_expense` WHERE YEAR(`Date`) = YEAR(CURDATE()) AND `Owner_admin_name_FK` = :boss GROUP BY MONTH(`Date`);";
$stmt2 = $Database->connect()->prepare($query2);
$stmt2->bindParam(':boss', $postedData['boss']);
$stmt2->execute();

if ($stmt2->rowCount() > 0) {
    $months = array();
    $total_expense = array();

    while ($row2 = $stmt2->fetch()) {
        $total_expense[] = $row2['total_expense'];
        $months[] = date("F", mktime(0, 0, 0, $row2["month"]));
    }

    echo json_encode(array(
        "months" => $months,
        "total_expense" => $total_expense,

    ));
} else {
    echo json_encode(array(

        "net_income" => "NO RECORDS!",
    ));
}
