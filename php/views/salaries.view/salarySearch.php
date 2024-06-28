<?php
require '../../classes/database.classes.php';
require '../../classes/salary.classes.php';
$postedData = json_decode(file_get_contents('php://input'),true);
$data = $postedData['value'];
$boss = $postedData['currentAdmin'];
    
    $Salary = new Salary;
    $stmt = $Salary->search_salary_table($data, $boss); // done

    $number = 1;
    $table = '<table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Employee ID</th>
                <th>PAY FREQUENCY</th>
                <th>RATE</th>
                <th>DAILY WORK HOURS</th>
                <th>WORK DAYS PER WEEK</th>
                <th>Salary</th>
                <th>Pay Date</th>
                <th>Operations</th>
            </tr>
        </thead>';
    while ($row = $stmt->fetch()) {
    $table .= '<tbody>
            <tr>
            <td>'.$number.'</td>
            <td>'.$row['empID_FK'].'</td>
            <td>'.$row['pay_frequency'].'</td>
            <td>₱ '.number_format($row['rate']).'</td>
            <td>'.$row['daily_work_hours'].'</td>
            <td>'.$row['work_days_per_week'].'</td>
            <td>₱ '.number_format($row['salary']).'</td>
            <td>'.$row['pay_date'].'</td>
            <td>
            <button onclick="taxCalculations(\''.$row['pay_frequency'].'\',\''.$row['rate'].'\',\''.$row['daily_work_hours'].'\',\''.$row['work_days_per_week'].'\',\''.$row['empID_FK'].'\')"class="text-button4"><a>+</a></button> 
            <button onclick="showUpdateForm(\''.$row['empID_FK'].'\')"class="text-button2"><a>Update</a></button>
            <button onclick="deleteSalaryData(\''.$row['empID_FK'].'\')"class="text-button3"><a>Delete</a></button> 
            <button onclick="paySalaryData(\''.$row['empID_FK'].'\',\''.$row['pay_frequency'].'\',\''.$row['salary'].'\')"class="text-button4"><a>Pay</a></button> 
            </td>
            </tr>
       </tbody>';
       $number++;
        }
        $table.='</table>';
        echo $table;

