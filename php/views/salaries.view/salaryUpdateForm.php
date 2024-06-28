<?php session_start(); // finished

require '../../classes/database.classes.php';
require '../../classes/salary.classes.php';

$postedData = json_decode(file_get_contents('php://input'), true);
$empID = $postedData['empID'];
$Salary = new Salary;
$datas = $Salary->get_employees_salary_data($empID);
$data;
while($fetch = $datas->fetch()){
    $data = $fetch;
}

echo '<form action="../../includes/salary.inc.php" method="POST">
    <table>
        <tr>
            <th>EMPLOYEE ID</th>
            <th>PAY FREQUENCY</th>
            <th>RATE</th>
            <th>WORK HOURS</th>
            <th>WORK DAYS PER WEEK</th>
        </tr>
        
        <tr>
            <td><input type="text" name="empID" value="'.$data['empID_FK'].'" disabled></td> 
            <td>
                <select name="payFrequency">
                    <option value="Hourly"'.(($data['pay_frequency'] === "Hourly") ? "Selected":"").'>Hourly</option>
                    <option value="Daily"'.(($data['pay_frequency'] === "Daily") ? "Selected":"").'>Daily</option>
                    <option value="Weekly"'.(($data['pay_frequency'] === "Weekly") ? "Selected":"").'>Weekly</option>
                    <option value="Semi-Monthly"'.(($data['pay_frequency'] === "Semi-Monthly") ? "Selected":"").'>Semi-Monthly</option>
                    <option value="Monthly"'.(($data['pay_frequency'] === "Monthly") ? "Selected":"").'>Monthly</option>
                </select>
            </td> 
            <td><input type="number" name="rate" value="'.$data['rate'].'"></td> 
            <td><input type="number" name="dailyWorkHours" value="'.$data['daily_work_hours'].'"></td> 
            <td><input type="number" name="workDaysPerWeek" value="'.$data['work_days_per_week'].'"></td> 
        </tr>
    </table>
    <button type="submit" name="salaryUpdate" value="'.$data['empID_FK'].'"class="text-button1"><a>Update</a></button>
</form>';


        

    
