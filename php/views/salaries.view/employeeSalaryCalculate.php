<?php 

require '../../classes/database.classes.php';
require '../../classes/employee.classes.php';
require '../../controller/employee.controller.php';

$rowID = $_GET['Calculate'];

$Employee = new Employee;
$stmt = $Employee->get_employees_salary_data($rowID);
//`paid_per`, `pay_per_date`, `work_hrs`, `work_days_per_week`, `#_of_Holidays`;
$data = array();
while($row = $stmt->fetch()) {
    $data[] = $row;
}
$paidPer = $data[0]['paid_per'];
$salary = $data[0]['pay_per_date'];
$workHours = $data[0]['work_hrs'];
$workDaysPerWeek = $data[0]['work_days_per_week'];
$numberOfHolidayAnnual = $data[0]['#_of_Holidays'];
$totalSalary;

$in_an_hour; // Your hourly rate
$in_a_day; // Daily earnings
$in_a_week; // Weekly earnings
$bi_weekly; // Biweekly earnings
$semi_monthly; // Semi-monthly earnings
$in_a_month; // Monthly earnings
$in_a_quarter_of_a_year; // Quarterly earnings
$in_a_year; // Yearly earnings

switch($paidPer) { // The chosen pay frequency
    case 'Per hour': 
            $in_an_hour = $salary;
            $in_a_day = $in_an_hour * $workHours;
            $in_a_week = $in_a_day * $workDaysPerWeek;
            
            $in_a_month = $in_a_week * 4; // Calculate monthly earnings
            $semi_monthly = $in_a_month / 2; // Calculate semi-monthly earnings (half of monthly earnings)
            $in_a_quarter_of_a_year = $in_a_month * 3; // Calculate quarterly earnings
            $in_a_year = $in_a_month * 12; // Calculate yearly earnings
            break;
    case 'Per day':
            $in_a_day = $salary; // Your daily rate
            $in_an_hour = $in_a_day / $workHours; // Calculate hourly rate based on daily earnings
            $in_a_week = $in_a_day * $workDaysPerWeek; // Calculate weekly earnings
            
            $in_a_month = $in_a_week * 4;
            $semi_monthly = $in_a_month / 2;
            $in_a_quarter_of_a_year = $in_a_month * 3;
            $in_a_year = $in_a_month * 12;
            break;
    case 'Per week':
            $in_a_week = $salary; // Your weekly rate
            $in_a_day = $in_a_week / $workDaysPerWeek; // Calculate daily earnings based on weekly rate
            $in_an_hour = $in_a_day / $workHours; // Calculate hourly rate based on daily earnings
            
            $in_a_month = $in_a_week * 4;
            $semi_monthly = $in_a_month / 2;   
            $in_a_quarter_of_a_year = $in_a_month * 3;
            $in_a_year = $in_a_month * 12;
            break;
    case 'Per two weeks':
            $bi_weekly = $salary; // Your bi-weekly rate
            $in_a_week = $bi_weekly / 2; // Calculate weekly earnings based on bi-weekly rate
            $in_a_day = $in_a_week / $workDaysPerWeek; // Calculate daily earnings based on weekly rate
            $in_an_hour = $in_a_day / $workHours; // Calculate hourly rate based on daily earnings
            $in_a_month = $in_a_week * 4;
            $semi_monthly = $in_a_month / 2;
            $in_a_quarter_of_a_year = $in_a_month * 3;
            $in_a_year = $in_a_month * 12;
            break;
    case 'Per month':
            $in_a_month = $salary; // Your monthly rate
            $in_a_week = $in_a_month / 4; // Calculate weekly earnings based on monthly rate
            $in_a_day = $in_a_week / $workDaysPerWeek; // Calculate daily earnings based on weekly rate
            $in_an_hour = $in_a_day / $workHours; // Calculate hourly rate based on daily earnings
            
            $semi_monthly = $in_a_month / 2;
            $in_a_quarter_of_a_year = $in_a_month * 3;
            $in_a_year = $in_a_month * 12;
            break;
    default:
        $in_an_hour = 0; // Your hourly rate
        $in_a_day = 0; // Daily earnings
        $in_a_week = 0; // Weekly earnings
        $bi_weekly = 0; // Biweekly earnings
        $semi_monthly = 0; // Semi-monthly earnings
        $in_a_month = 0; // Monthly earnings
        $in_a_quarter_of_a_year = 0; // Quarterly earnings
        $in_a_year = 0; // Yearly earnings

    }?>
    <br><br><br><br><br>
    <h1><?=$rowID . "/'s Salary"?></h1>
    <br><br>
    <table>
    <tr>
        <th>In an hour</th>
        <th>In a day</th>
        <th>In a week</th>
        <th>Bi-weekly</th>
        <th>Semi-monthly</th>
        <th>In a month</th>
        <th>Quarterly</th>
        <th><select id="annual_salary">
          <option value="annualBase">Annual</option>
          <option value="annualWithHolidayPay">Annual w/ Holiday Pay</option>
          <option value="annualMinusHoliday">Annual - Holiday</option>
        </select></th>
    </tr>
    <tr>
        <td><?="₱".$in_an_hour?></td>
        <td><?="₱".$in_a_day?></td>
        <td><?="₱".$in_a_week?></td>
        <td><?="₱".$bi_weekly?></td>
        <td><?="₱".$semi_monthly?></td>
        <td><?="₱".$in_a_month?></td>
        <td><?="₱".$in_a_quarter_of_a_year?></td>
        <td id = "output"></td>
    </tr>
    </table>
    <script>
      const button = document.getElementById("annual_salary");
      const tableData = document.getElementById("output");
      tableData.innerHTML = "₱"+<?=$in_a_year;?>;
      button.addEventListener("click", (e) => {
        setTimeout( ()=> {
          e.preventDefault();
          console.log(button.value);
          switch (button.value) {
            case 'annualWithHolidayPay':
              tableData.innerHTML = "₱"+<?=$annualEarnings = $in_a_year + ($in_a_day * $numberOfHolidayAnnual);?>;
              break;
            case 'annualMinusHoliday':
              tableData.innerHTML = "₱"+<?=$annualEarnings = $in_a_year - ($in_a_day * $numberOfHolidayAnnual);?>;
              break;
            case 'annualBase':
              tableData.innerHTML = "₱"+<?=$in_a_year;?>;
              break;
            default:
              tableData.innerHTML = "₱"+<?=$in_a_year;?>;
            break;
          }
        },100)
       
      })
    </script>


