<?php session_start();
require '../../classes/database.classes.php';
require '../../classes/salary.classes.php';

$postedData = json_decode(file_get_contents("php://input"), true);
$data = $postedData['currentAdmin'];

$Salary = new Salary;
$fetchdata = $Salary->show_salary_history_table($data);

$table = '<table>
      <tr>
          <th>ID</th>
          <th>Employee ID</th>
          <th>Pay frequency</th>
          <th>Paid Date</th>
          <th>Next Paid Date</th>
          <th>Salary</th>
          <th>Operations</th>
      </tr>';
$number = 1;
foreach ($fetchdata as $datas) {
    $table .= '<tr>
        <td>' . $number . '</td>
        <td>' . $datas['empID_FK'] . '</td>
        <td>' . $datas['pay_frequency'] . '</td>
        <td>' . $datas['paid_date'] . '</td>
        <td>' . $datas['next_paid_date'] . '</td>
        <td>₱ ' . number_format($datas['salary']) . '</td>
        <td>
        <button onclick="deleteHistorySalaryData(\'' . $datas['ID'] . '\',\'' . $datas['Owner_admin_name_FK'] . '\' )"class="text-button3"><a>Delete</a></button>
        </td>
        </tr>';
    $number++;
}
$table .= '</table>';
echo $table;
