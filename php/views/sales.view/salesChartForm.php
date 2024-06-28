<?php

require '../../classes/database.classes.php';

$postedData = json_decode(file_get_contents('php://input'), true);
$Database = new Database();

$query = "SELECT MONTH(`date`) AS 'month', COUNT(*) AS total_orders, SUM(`total`) AS total_sales FROM `sales` WHERE YEAR(`date`) = YEAR(CURDATE()) AND `Owner_admin_name_FK` = :boss GROUP BY MONTH(`date`);";
$stmt = $Database->connect()->prepare($query);
$stmt->bindParam(':boss', $postedData['boss']);
$stmt->execute();

$queryItemSales = "SELECT `item_name`, SUM(`total`) AS total_item_sales FROM `sales` WHERE `Owner_admin_name_FK` = :boss GROUP BY `item_name`;";
$stmt2 = $Database->connect()->prepare($queryItemSales);
$stmt2->bindParam(':boss', $postedData['boss']);
$stmt2->execute();

$query3 = "SELECT DATE(`date`) AS daily, COUNT(*) AS daily_total_orders, SUM(`total`) AS daily_total_sales
          FROM `sales`
          WHERE YEAR(`date`) = YEAR(CURDATE()) AND `Owner_admin_name_FK` = :boss
          GROUP BY DATE(`date`);";
$stmt3 = $Database->connect()->prepare($query3);
$stmt3->bindParam(':boss', $postedData['boss']);
$stmt3->execute();

if ($stmt->rowCount() > 0) {
    $months = array();
    $totalOrders = array();
    $totalSales = array();
    $item_name = array();
    $item_name_total_sales = array();

    $days = array();
    $daily_total_item_sales = array();
    $daily_total_total_orders = array();

    while ($row = $stmt->fetch()) {
        // Collect monthly data for Chart.js
        $months[] = date("F", mktime(0, 0, 0, $row["month"])); # to format number month into words
        $totalOrders[] = $row['total_orders'];
        $totalSales[] = $row['total_sales'];

    }

    while ($row2 = $stmt2->fetch()) {
        $item_name[] = $row2['item_name'];
        $item_name_total_sales[] = $row2['total_item_sales'];

    }

    while ($row3 = $stmt3->fetch()) {
        $days[] = $row3['daily'];
        $daily_total_item_sales[] = $row3['daily_total_sales'];
        $daily_total_orders[] = $row3['daily_total_orders'];

    }

    echo json_encode(array(
        "months" => $months,
        "totalOrders" => $totalOrders,
        "totalSales" => $totalSales,
        "item_name" => $item_name,
        "item_name_total_sales" => $item_name_total_sales,

        "daily_item_total_sales" => $daily_total_item_sales,
        "days" => $days,
        "daily_total_orders" => $daily_total_orders,
    ));
} else {
    echo json_encode(array(
        "months" => 0,
        "totalOrders" => 0,
        "totalSales" => 0,
        "item_name" => 0,
        "item_name_total_sales" => 0,

        "daily_item_total_sales" => 0,
        "days" => 0,
        "daily_total_orders" => 0,
    ));
}
