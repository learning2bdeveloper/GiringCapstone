<?php
require '../../classes/database.classes.php';
require '../../classes/expense.classes.php';
$postedData = json_decode(file_get_contents('php://input'), true);
$data = $postedData['value'];
$boss = $postedData['currentAdmin'];

$Expense = new Expense;
$stmt = $Expense->search_expense_table($data, $boss);

$number = 1;
$table = '<table>
        <thead>
            <tr>

                <th>Date</th>
                <th>Due</th>
                <th>Payee</th>
                <th>Category</th>
                <th>Category Amount</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Date Updated</th>
                <th>Employee ID </th>
                <th>Operations</th>
            </tr>
        </thead>';
while ($row = $stmt->fetch()) {
    $table .= '<tbody>
            <tr>

            <td>' . $row['Date'] . '</td>
            <td>' . $row['Due'] . '</td>
            <td>' . $row['Payee'] . '</td>
            <td>' . $row['Category'] . '</td>
            <td>' . $row['Category_amount'] . '</td>
            <td>' . $row['Description'] . '</td>
            <td>' . "₱ " . $row['Amount'] . '</td>
            <td>' . $row['Update_date'] . '</td>
            <td>' . $row['empID_FK'] . '</td>
            <td>
            <button onclick="showUpdateForm(' . $row['ID'] . ')" class="text-button2"><a>Update</a></button>
            <button onclick="deleteExpenseData(' . $row['ID'] . ', ' . $row['Category_amount'] . ', \'' . $row['Category'] . '\')" class="text-button3"><a>Delete</a></button>
            </td>
            </tr>
       </tbody>';
    $number++;
}
$table .= '</table>';
echo $table;
