<?php
require '../../classes/database.classes.php';
require '../../classes/budget.classes.php';
$postedData = json_decode(file_get_contents('php://input'), true);
$data = $postedData['value'];
$boss = $postedData['currentAdmin'];

$table;

$budget = new Budget;
$stmt = $budget->search_budget_table($data, $boss);

$number = 1;
$table = '<table>
        <thead>
            <tr>
            <th>ID</th>
            <th>Admin</th>
            <th>Date</th>
            <th>Category</th>
            <th>Description</th>
            <th>Amount</th>
            <th>Date Updated</th>
            <th>Operations</th>
            </tr>
        </thead>';
while ($row = $stmt->fetch()) {
    $table .= '<tbody>
            <tr>
            <td>' . $number . '</td>
            <td>' . $row['Owner_admin_name_FK'] . '</td>
            <td>' . $row['Date'] . '</td>
            <td>' . $row['Category'] . '</td>
            <td>' . $row['Description'] . '</td>
            <td>' . "₱ " . number_format($row['Amount']) . '</td>
            <td>' . $row['Update_date'] . '</td>
            <td>
            <button onclick="showUpdateForm(' . $row['ID'] . ')"class="text-button2"><a>Update</a></button>
            <button onclick="deleteBudgetData(' . $row['ID'] . ')"class="text-button3"><a>Delete</a></button>
            </td>
            </tr>
       </tbody>';
    $number++;
}
$table .= '</table>';
echo $table;
