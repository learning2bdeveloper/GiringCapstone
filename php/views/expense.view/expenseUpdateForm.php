<?php
require '../../classes/database.classes.php';
require '../../classes/expense.classes.php';

$postedData = json_decode(file_get_contents('php://input'),true);

$Expense = new Expense;
$fetch = $Expense->show_expense_data_by_id($postedData['id']);

$data;
while($datas = $fetch->fetch()) {
    $data = $datas;
}
        $table = '
        <table id = "table">

                <tr>
                    <th>Category</th>
                    <th>Category\'s budget</th>
                    <th>Remark</th>
                </tr>

                <tr>
                    <td><input type="text" id="category" value="'.$data['Category'].'" disabled></td> 
                    <td><input type="number" id="budgetAmount" value="'.$data['Category_amount'].'" disabled></td>
                    <td><textarea id="description" cols="30" rows="2">'.$data['Description'].'</textarea></td>
                </tr>

                </table>

                <br>
                <button type="submit" onclick="updateExpense('.$data['ID'].', \''.$data['Category_amount'].'\')" class="text-button1"><a>Update</a></button>';
        echo $table;
