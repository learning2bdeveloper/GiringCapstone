<?php session_start(); // finished

require '../../classes/database.classes.php';
require '../../classes/salary.classes.php';

$Salary = new Salary;
$datas = $Salary->get_employees_id($_SESSION['admin_name']);

?>
<form action="../../includes/salary.inc.php" method="POST">
    <table>
        <tr>
            <th>EMPLOYEE ID</th>
            <th>PAY FREQUENCY</th>
            <th>RATE</th>
            <th>WORK HOURS</th>
            <th>WORK DAYS PER WEEK</th>
        </tr>
        
        <tr>
            <td>
                <select name="empIDs">
                    <?php while($data = $datas->fetch()) {
                        echo  '<option value="'.$data['empID'].'">'.$data['empID'].'</option>';
                    } ?>
                </select>
            </td> 
            <td>
                <select name="payFrequency">
                    <option value="Hourly">Hourly</option>
                    <option value="Daily">Daily</option>
                    <option value="Weekly">Weekly</option>
                    <option value="Semi-Monthly">Semi-Monthly</option>
                    <option value="Monthly">Monthly</option>
                </select>
            </td> 
            <td><input type="number" name="rate"></td> 
            <td><input type="number" name="dailyWorkHours"></td> 
            <td><input type="number" name="workDaysPerWeek"></td> 
        </tr>
    </table>
    <button type="submit" name="salaryAdd"class="text-button1"><a>COMPUTE</a></button>
</form>


        

    
