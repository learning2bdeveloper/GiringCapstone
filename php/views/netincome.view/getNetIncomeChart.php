<?php

require '../../classes/database.classes.php';

$postedData = json_decode(file_get_contents('php://input'), true);
$Database = new Database();

$query = "SELECT MONTH(`date`) AS 'month', SUM(`total`) AS total_sales FROM `sales` WHERE YEAR(`date`) = YEAR(CURDATE()) AND `Owner_admin_name_FK` = :boss GROUP BY MONTH(`date`);";
$stmt = $Database->connect()->prepare($query);
$stmt->bindParam(':boss', $postedData['boss']);
$stmt->execute();

$query2 = "SELECT MONTH(`Date`) AS 'month', SUM(`Amount`) AS total_amount FROM `active_expense` WHERE YEAR(`Date`) = YEAR(CURDATE()) AND `Owner_admin_name_FK` = :boss GROUP BY MONTH(`Date`);";
$stmt2 = $Database->connect()->prepare($query2);
$stmt2->bindParam(':boss', $postedData['boss']);
$stmt2->execute();

if ($stmt->rowCount() > 0 && $stmt2->rowCount() > 0) {
    $months = array();
    $total_sales = array();
    $total_amount = array();

    while ($row = $stmt->fetch()) {
        $months[] = date("F", mktime(0, 0, 0, $row["month"]));
        $total_sales[] = $row['total_sales'];
    }

    while ($row2 = $stmt2->fetch()) {
        $total_amount[] = $row2['total_amount'];
    }

    $netIncome = array();
    for ($i = 0; $i < count($months); $i++) {
        $netIncome[] = $total_sales[$i] - $total_amount[$i];
    }

    if (!empty($months)) {
        echo json_encode(array(
            "months" => $months,
            "total_sales" => $total_sales,
            "total_amount" => $total_amount,
            "net_income" => $netIncome,
        ));
    }
} else {
    echo json_encode(array(

        "net_income" => "NO RECORDS!",
    ));
}
