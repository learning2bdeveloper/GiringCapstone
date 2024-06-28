<?php
require '../../classes/database.classes.php';
require '../../classes/sales.classes.php';
$postedData = json_decode(file_get_contents('php://input'), true);
$data = $postedData['value'];
$boss = $postedData['currentAdmin'];

$Sales = new Sales;
$stmt = $Sales->search_sales_table($data, $boss);

$number = 1;
$table = '<table>
        <thead>
            <tr>
            <th>Date</th>

            <th>Item Name</th>
            <th>Item Description</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Sales ID</th>
            <th>Employee ID</th>
            <th>Employee Name</th>
            <th>Operations</th>
            </tr>
        </thead>';
while ($row = $stmt->fetch()) {
    $id = $row['ID'];
    $salesID = $row['salesID'];
    $date = $row['date'];
    $empID = $row['empID_FK'];
    $empName = $row['empID_Name'];
    $item_name = $row['item_name'];
    $item_description = $row['description'];
    $quantity = $row['quantity'];
    $total = $row['total'];
    $amount = $row['amount'];
    $table .= '<tr>
    <td>' . $date . '</td>

    <td>' . $item_name . '</td>
    <td>' . $item_description . '</td>
    <td>' . "₱ " . number_format($amount) . '</td>
    <td>' . $quantity . '</td>
    <td>' . "₱ " . number_format($total) . '</td>
    <td>' . $salesID . '</td>
    <td>' . $empID . '</td>
    <td>' . $empName . '</td>
    <td>
    <button onclick="deleteSalesData(' . $id . ')"  onclick = "return confirm(\'Are you sure you want to delete this?\')"class="text-button3"><a>DELETE</a></button>
    </td>
            </tr>';

}
$table .= '</table>';
echo $table;
