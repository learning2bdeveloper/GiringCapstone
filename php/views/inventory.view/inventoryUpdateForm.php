<?php
require '../../classes/database.classes.php';
require '../../classes/inventory.classes.php';
$postedData = json_decode(file_get_contents('php://input'),true);

$Inventory = new Inventory;
$fetch = $Inventory->get_data_by_id($postedData['id']);

$data;
while($datas = $fetch->fetch()) {
    $data = $datas;
}
        $table = '
        <table>

            <tr>
                <th>Item No.</th>
                <th>Item Name</th>
                <th>Item Description</th>
                <th>Quantity</th>
                <th>Amount</th>
            </tr>

            <tr>
                <td><input type="text" id="itemNo" value="'.$data['item_no'].'"></td>
                <td><input type="text" id="itemName" value="'.$data['item_name'].'"></td>
                <td><textarea id="itemDescription" cols="30" rows="2">'.$data['item_description'].'</textarea></td>
                <td><input type="text" id="quantity" value="'.$data['quantity'].'"></td>
                <td><input type="text" id="amount" value="'.$data['amount'].'"></td>
            </tr>

        </table>
        <br>
        <button type="submit" onclick="updateInventory('.$data['ID'].',\''.$data['item_no'].'\',\''.$data['item_name'].'\')"class="text-button1"><a>UPDATE</a></button>';
        echo $table;
