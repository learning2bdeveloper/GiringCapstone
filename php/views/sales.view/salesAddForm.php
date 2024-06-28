

<form action="../../includes/sales.inc.php" method="post">
                <table id="innerTable">

                <tr> <!-- automatic ang name kag empID kag datetime -->
                    <th>Item</th>
                    <th>Sales Description</th>
                    <th>Amount</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>

                <tr>
                    <td><input type="text" name="itemName"></td> <!--  name="" NEEDS TO HAVE [] TO BE CONSIDERED ARRAY -->
                    <td><textarea name="itemDescription" cols="30" rows="2"></textarea></td>
                    <td>₱<input type="number" name="amount" id="amount" oninput="calculateTotal()"></td>
                    <td><input type="number" name="quantity" id="quantity" oninput="calculateTotal()"></td>
                    <td>₱<input type="number" name="total" id="total" readonly="true"></td>
                </tr>

                </table>

                <br>
                <button type="submit" name="salesAdd"  onclick = "return confirm('Are you sure you want to add this?')"class="text-button1"><a>ADD SALES</a></button>
            </form>
