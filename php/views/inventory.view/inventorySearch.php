<?php
require '../../classes/database.classes.php';
require '../../classes/inventory.classes.php';
$postedData = json_decode(file_get_contents('php://input'), true);
$data = $postedData['value'];
$boss = $postedData['currentAdmin'];

$Inventory = new Inventory;
$stmt = $Inventory->search_inventory_table($data, $boss);

$number = 1;
$table = '<table>
        <thead>
            <tr>
            <th>Employee ID</th>
            <th>Date</th>
            <th>Item No.</th>
            <th>Item Name</th>
            <th>Item Description</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Amount</th>
            <th>Date Modified</th>
            <th>Operations</th>
            </tr>
        </thead>';
while ($row = $stmt->fetch()) {
    $id = $row['ID'];
    $employeeID = $row['empID_FK'];
    $date = $row['date'];
    $item_no = $row['item_no'];
    $item_name = $row['item_name'];
    $item_description = $row['item_description'];
    $quantity = $row['quantity'];
    $price = $row['price'];
    $amount = $row['amount'];
    $date_modified = $row['modified_date'];
    $table .= '<tr>
            <td>' . $employeeID . '</td>
            <td>' . $date . '</td>
            <td>' . $item_no . '</td>
            <td>' . $item_name . '</td>
            <td>' . $item_description . '</td>
            <td>' . $quantity . '</td>
            <td>' . $price . '</td>
            <td>' . "₱ " . number_format($amount) . '</td>
            <td>' . $date_modified . '</td>
            <td>
            <button onclick="showUpdateForm(' . $id . ')"class="text-button2"><a>Update</a></button>
            <button onclick="deleteInventoryData(' . $id . ',' . $amount . ')"  onclick = "return confirm(\'Are you sure you want to delete this?\')"class="text-button3"><a>Delete</a></button>
            </td>
            </tr>';

}
$table .= '</table>';
echo $table;
