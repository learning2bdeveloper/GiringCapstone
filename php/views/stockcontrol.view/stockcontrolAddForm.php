<?php session_start();
require '../../classes/database.classes.php';
require '../../classes/inventory.classes.php';

$Inventory = new Inventory;
$datas = $Inventory->get_item_no($_SESSION['admin_name']); # query to get the itemNo in inventory

$table = ' <form action="../../includes/stockcontrol.inc.php" method="POST">

        <table>
            <tr>
                <th>Item No</th>
                <th>Item Name</th>
                <th>Item Description</th>
            </tr>

            <tr>
                <td>  <select id="item_no" onclick="get_item_name_item_description_price(this)" name="itemNo">';
while ($data = $datas->fetch()) { # if ano ang na gwa sa gin fetch then amo na ang value na e send sa onclick function para mag kwa name, description, price.
    $table .= '<option value="' . $data['item_no'] . '">' . $data['item_no'] . '</option>';
}
$table .= '</select></td>
                <td><input type="text" id="itemName" name="itemName" readonly="true"></td>
                <td><textarea id="itemDescription" cols="30" rows="2" name="itemDescription" readonly="true"></textarea></td>
            </tr>

            <tr>
                <th>Price</th>
                <th>Quantity</th>
                <th>Amount</th>
            </tr>

            <tr>
                <td><input type="number" id="price" name="price" readonly="true"></td>
                <td><input type="number" id="quantity" name="quantity" oninput="calculateAmount()"></td>
                <td><input type="number" id="amount" name="amount" readonly="true"></td>
            </tr>

            <tr>
                <th>Tax Rate %</th>
                <th>Tax</th>
                <th>Total</th>
            </tr>

            <tr>
                <td><input type="number" id="tax_rate" name="tax_rate" oninput="calculateTaxAndTotal()"></td>
                <td><input type="number" id="tax" name="tax" readonly="true"></td>
                <td><input type="number" id="total" name="total" readonly="true"></td>
            </tr>
        </table>
                <button name="createstockcontrol" onclick = "return confirm(\'Are you sure you want to add this?\')"class="text-button1"><a>ADD Stock</a></button>
            </form>';
echo $table;
