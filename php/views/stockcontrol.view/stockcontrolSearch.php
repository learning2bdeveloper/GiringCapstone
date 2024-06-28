<?php
require '../../classes/database.classes.php';
require '../../classes/revenue.classes.php';
$postedData = json_decode(file_get_contents('php://input'), true);
$data = $postedData['value'];
$boss = $postedData['currentAdmin'];

$Revenue = new Revenue;
$stmt = $Revenue->search_revenue_table($data, $boss);

$number = 1;
$table = '<table>
        <thead>
            <tr>
            <th>Employee ID</th>
            <th>Date</th>
            <th>Item No</th>
            <th>Item Name</th>
            <th>Item Description</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Amount</th>
            <th>Tax Rate</th>
            <th>Tax</th>
            <th>Total</th>
            <th>Date Updated</th>
            <th>Operations</th>
            </tr>
        </thead>';
while ($row = $stmt->fetch()) {

    $table .= '<tr>
    <td>' . $row['empID_FK'] . '</td>
    <td>' . $row['date'] . '</td>
    <td>' . $row['item_no'] . '</td>
    <td>' . $row['item_name'] . '</td>
    <td>' . $row['item_description'] . '</td>
    <td>' . $row['price'] . '</td>
    <td>' . $row['quantity'] . '</td>
    <td>' . "₱ " . number_format($row['amount']) . '</td>
    <td>' . $row['tax_rate'] . '</td>
    <td>' . $row['tax'] . '</td>
    <td>' . $row['total'] . '</td>
    <td>' . $row['modified_date'] . '</td>
    <td>
    <button onclick="deleteRevenueData(' . $row['ID'] . ',\'' . $row['item_no'] . '\',\'' . $row['quantity'] . '\')"class="text-button3"><a>Delete</a></button>
    </td>
            </tr>';

    $number++;
}
$table .= '</table>';
echo $table;
