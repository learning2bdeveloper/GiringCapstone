<?php
require '../../functions/salaryTaxCalculationsShowDatas.function.php';
require '../../classes/database.classes.php';
require '../../classes/employee.classes.php';

$pay_frequency = $_POST['pay_frequency'];
$rate = $_POST['rate'];
$daily_work_hours = $_POST['daily_work_hours'];
$work_days_per_week = $_POST['work_days_per_week'];
$currentAdmin = $_POST['currentAdmin'];
$empID = $_POST['empID'];

$datas = showCalculatedTaxDatas($pay_frequency, $rate, $daily_work_hours, $work_days_per_week);

$Employee = new Employee;
$data = $Employee->get_employees_data($empID);

while ($row = $data->fetch()) {
    $employeeData = $row;
}
?>
    <!--  IMPORTANT -->


    <table>
    <tr>
      <th>Employee ID</th>
      <td><?=$empID . "/'s salary info"?></td>
      <th>Name</th>
      <td><?=$employeeData['first_name'] . " " . $employeeData['last_name'];?></td>
    </tr>
  </table>

  <table>
    <tr>
      <th>Deductions</th>
      <th>Amount</th>
      <th>Deductions</th>
      <th>Amount</th>
    </tr>
    <tr>
      <td>SSS</td>
      <td><?="₱" . number_format($datas['SSS']);?></td>
      <td>PAG-IBIG</td>
      <td><?="₱" . number_format($datas['pagIbig']);?></td>
    </tr>
    <tr>
      <td>Employee</td>
      <td><?="₱" . number_format($datas['EmployeeSSS']);?></td>
      <td>Employee</td>
      <td><?="₱" . number_format($datas['EmployeePagIbig']);?></td>
    </tr>
    <tr>
      <td>Employer</td>
      <td><?="₱" . number_format($datas['EmployeerSSS']);?></td>
      <td>Employer</td>
      <td><?="₱" . number_format($datas['EmployeerPagIbig']);?></td>
    </tr>
  </table>

  <table>
    <tr>
      <th>Deductions</th>
      <th>Amount</th>
      <th>Deductions</th>
      <th>Amount</th>
    </tr>
    <tr>
      <td>Phil-Health</td>
      <td><?="₱" . number_format($datas['Philhealth']);?></td>
      <td>Employee Salary - Tax</td>
      <td><?="₱" . number_format($datas['total_salary']);?></td>
    </tr>
    <tr>
      <td>Employee</td>
      <td><?="₱" . number_format($datas['EmployeePhilhealth']);?></td>
      <td>Employer Total Tax Payment</td>
      <td><?="₱" . number_format($datas['total_employer']);?></td>
    </tr>
    <tr>
      <td>Employer</td>
      <td><?="₱" . number_format($datas['EmployeerPhilhealth']);?></td>
      <td>Income Tax</td>
      <td><?="₱" . number_format($datas['income_tax']);?></td>
    </tr>
  </table>

  <table>
    <tr>
      <th>Deductions</th>
      <th>Amount</th>
      <th>Deductions</th>
      <th>Amount</th>
    </tr>
    <tr>
      <td>Hourly</td>
      <td><?="₱" . number_format($datas['in_an_hour']);?></td>
      <td>Daily</td>
      <td><?="₱" . number_format($datas['in_a_day']);?></td>
    </tr>
    <tr>
      <td>Weekly</td>
      <td><?="₱" . number_format($datas['in_a_week']);?></td>
      <td>Semi-Monthly</td>
      <td><?="₱" . number_format($datas['semi_monthly']);?></td>
    </tr>
    <tr>
      <td>Monthly</td>
      <td><?="₱" . number_format($datas['in_a_month']);?></td>
      <td>Annual</td>
      <td><?="₱" . number_format($datas['in_a_year']);?></td>
    </tr>
  </table>

  <table>
    <tr>
        <th>Gross Salary</th>
        <th>Total Deduction (Montly)</th>
        <th>Net Pay</th>
    </tr>
        <td><?="₱" . number_format($datas['in_a_year']);?></td>
        <td><?="₱" . number_format($datas['total_tax']);?></td>
        <td><input type="text" id="netPay"></td>
    </tr>
</table>


