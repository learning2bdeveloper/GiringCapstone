<?php session_start();
require '../../classes/database.classes.php';
require '../../classes/budget.classes.php';

$category = new Budget;
$datas = $category->getCategories($_SESSION['admin_name']);

?>



                <table id = "table">

                <tr>
                    <!-- date is current -->
                    <th>Expense Category</th>
                    <th>Category's Budget</th>
                    <th>Payee</th>
                    <th>Remark</th>
                    <th>Amount</th>
                    <th>Due date (YYYY-MM-DD)</th>
                </tr>

                <tr>
                    <td><select id="category" onclick="category_budget(this)">
                        <?php while ($data = $datas->fetch()) {
    echo '<option value="' . $data['Category'] . '">' . $data['Category'] . '</option>';
}?>
                    </select></td>
                    <td><input type="number" id="budgetAmount" disabled></td>
                    <td><input type="text" id="payee"></td>
                    <td><textarea id="description" cols="30" rows="2"></textarea></td>
                    <td><input type="text" id="expenseAmount"></td>
                    <td><input type="text" id="due_date"></td>
                </tr>

                </table>

                <br>
                <button onclick="AddData()"class="text-button1"><a>ADD EXPENSE</a></button>


