


             <form action="../../includes/inventory.inc.php" method="post">
                <table id="innerTable">

                <tr>
                    <th>Item Name</th>
                    <th>Item Description</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>

                <tr>
                    <td><input type="text" name="itemName"></td> <!--  name="" NEEDS TO HAVE [] TO BE CONSIDERED ARRAY -->
                    <td><textarea name="itemDescription" cols="30" rows="2"></textarea></td>
                    <td><input type="number" name="quantity" id="quantity"></td>
                    <td>₱<input type="number" name="price" id="price"></td>
                </tr>

                </table>

                <br>
                <button type="submit" name="inventoryAdd"  onclick = "return confirm('Are you sure you want to add this?')"class="text-button1"><a>ADD ITEM</a></button>
            </form>
