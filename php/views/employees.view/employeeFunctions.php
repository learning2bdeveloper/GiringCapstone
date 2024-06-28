<?php session_start();
include_once '../../classes/database.classes.php';
include_once '../../classes/employee.classes.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <?php

    if (isset($_GET['update'])) {
        $_SESSION['current_ID'] = $_GET['update'];
        $_SESSION['current_Phone'] = $_GET['phone'];

        $employeeInfo = new Employee;
        $datas = $employeeInfo->get_employees_data($_GET['update']);
        $info;

        foreach ($datas as $data) {
            $info = $data;
        }
        echo '<h4>UPDATE Employee\'s INFO </h4> 
        <form action="./php/includes/employee.inc.php" method="post">
        <input type="text" name="empID" placeholder="Employee ID" value="' . $info['empID'] . '">
        <br>
        <input type="text" name="empName" placeholder="Name" value="' . $info['name'] . '">
        <br>
        <input type="text" name="empAddress" placeholder="Address" value="' . $info['address'] . '">
        <br>
        <input type="date" name="empBirthdate" placeholder="Birthday" value="' . $info['birth_date'] . '">
        <br>
        <input type="number" name="empPhone" placeholder="Phone Number" value="' . $info['phone'] . '">
        <br>
        <input type="password" name="empPwd" placeholder="Password" value="' . $info['password'] . '">
        <br>
        <br>
        <button type="submit" name="empUpdate">Update</button>
        </form>';

        if (isset($_SESSION['employee_Set_Employee_Salary_Error'])) {
            $errors = $_SESSION['employee_Set_Employee_Salary_Error'];

            foreach ($errors as $error) {
                echo '<p>' . $error . '</p>';
            }

            unset($_SESSION['employee_Set_Employee_Salary_Error']);
        }
    }


    if (isset($_GET['add'])) {

        echo '<h4>Add Eployees</h4>
        <form action="../../includes/employee.inc.php" method="post">
        <input type="text" name="empID" placeholder="Employee ID">
        <br>
        <input type="text" name="empName" placeholder="Name">
        <br>
        <input type="text" name="empAddress" placeholder="Address">
        <br>
        <input type="date" name="empBirthdate" placeholder="Birthday">
        <br>
        <input type="number" name="empPhone" placeholder="Phone Number">
        <br>
        <input type="password" name="empPwd" placeholder="Password">
        <br>
        <br>
        <button type="submit" name="empCreate">Create</button>
        </form>';

        if (isset($_SESSION['employee_Create_Error'])) {
            $errors = $_SESSION['employee_Create_Error'];

            foreach ($errors as $error) {
                echo '<p>' . $error . '</p>';
            }

            unset($_SESSION['employee_Create_Error']);
        }
    }

    if (isset($_GET['salary'])) {
        echo '<h2> Set Salary </h2>
    <form action="../../includes/employee.inc.php" method="post">
    <input type="text" name="empID" placeholder="Employee ID" value="'.$_GET['salary'].'">
    <br>
    <select name="get_paid" required>
            <option value="Per hour">Per hour</option>
            <option value="Per day">Per day</option>
            <option value="Per week">Per week</option>
            <option value="Per two weeks">Per 2 weeks</option>
            <option value="Per month">Per month</option>
            </select>
            <br>
    <input type="number" name="salary" placeholder="Salary">
    <br>
    <input type="number" name="hrs_per_day" placeholder="Hours per Day">
    <br>
    <input type="number" name="days_per_week" placeholder="Days per Week">
    <br>
    <input type="number" name="holiday_per_year" placeholder="Holidays per Year">
    <br>
    <input type="number" name="paid_vacation_annual" placeholder="Paid Vacation Annual">
    <br>
    <button type="submit" name="empSetSalary">SET</button>
    </form>';

        if (isset($_SESSION['employee_Set_Employee_Salary_Error'])) {
            $errors = $_SESSION['employee_Set_Employee_Salary_Error'];

            foreach ($errors as $error) {
                echo '<p>' . $error . '</p>';
            }

            unset($_SESSION['employee_Set_Employee_Salary_Error']);

            echo ' <p>Please click <a href="employee.php">here</a> to go back Employee\'s page </p>';
        }
    }
////////////////////////////////////////////////////////
    if (isset($_GET['updateSalary'])) {
        $_SESSION['current_ID'] = $_GET['updateSalary'];
          
        $employeeInfo = new Employee;
        $datas = $employeeInfo->get_employees_salary_data($_GET['updateSalary']);
        
        foreach ($datas as $data) {
            $_SESSION['data']= $data;
        }

        function output() {

        echo '<h4>UPDATE '.$_GET['updateSalary'].'\'s Employee Salary Data </h4> 
        <form action="../../includes/employee.inc.php" method="post">';
                if(isset($_SESSION['data']['paid_per'])) {

                    switch($_SESSION['data']['paid_per']) {
                        case 'Per hour':
                              echo '<select name="get_paid" required>
                  <option value="Per hour">Per hour</option>
                    <option value="Per day">Per day</option>
                    <option value="Per week">Per week</option>
                    <option value="Per two weeks">Per 2 weeks</option>
                    <option value="Per month">Per month</option>
                    </select>';
                    break;
                    case 'Per day':
                        echo ' <select name="get_paid" required>
                        <option value="Per day">Per day</option>
                        <option value="Per hour">Per hour</option>
                        <option value="Per week">Per week</option>
                        <option value="Per two weeks">Per 2 weeks</option>
                        <option value="Per month">Per month</option>
                        </select>';
                    break;
                    case 'Per week':
                        echo ' <select name="get_paid" required>
                        <option value="Per week">Per week</option>
                        <option value="Per hour">Per hour</option>
                        <option value="Per day">Per day</option>
                        <option value="Per two weeks">Per 2 weeks</option>
                        <option value="Per month">Per month</option>
                        </select>';
                    break;
                    case 'Per two weeks':
                        echo ' <select name="get_paid" required>
                        <option value="Per two weeks">Per 2 weeks</option>
                        <option value="Per hour">Per hour</option>
                        <option value="Per day">Per day</option>
                        <option value="Per week">Per week</option>
                        <option value="Per month">Per month</option>
                        </select>';
                    break;
                    case 'Per month':
                        echo ' <select name="get_paid" required>
                        <option value="Per month">Per month</option>
                        <option value="Per hour">Per hour</option>
                        <option value="Per day">Per day</option>
                        <option value="Per week">Per week</option>
                        <option value="Per two weeks">Per 2 weeks</option>
                        </select>';
                    break;
                    }
                }else {
                     echo ' <select name="get_paid" required>
                     <option value="Per hour">Per hour</option>
                        <option value="Per day">Per day</option>
                        <option value="Per week">Per week</option>
                        <option value="Per two weeks">Per 2 weeks</option>
                        <option value="Per month">Per month</option>
                        </select>';
                }
                  echo'<br>
            <input type="number" name="salary" placeholder="Salary" value="'.$_SESSION['data']['pay_per_date'].'">
            <br>
            <input type="number" name="hrs_per_day" placeholder="Hours per Day" value="'.$_SESSION['data']['work_hrs'].'">
            <br>
            <input type="number" name="days_per_week" placeholder="Days per Week" value="'.$_SESSION['data']['work_days_per_week'].'">
            <br>
            <input type="number" name="holiday_per_year" placeholder="Holidays per Year" value="'.$_SESSION['data']['#_of_Holidays'].'">
            <br>
            <input type="number" name="paid_vacation_annual" placeholder="Paid Vacation Annual" value="'.$_SESSION['data']['Paid_vacation_annual'].'">
            <br>
            <button type="submit" name="empUpdateSalary" class="text-button"><a>Update</a></button>
            </form>';
            }
                
            output();
          
        if (isset($_SESSION['employee_Update_Employee_Salary_Error'])) {
            $errors = $_SESSION['employee_Update_Employee_Salary_Error'];

            foreach ($errors as $error) {
                echo '<p>' . $error . '</p>';
            }

            unset($_SESSION['employee_Update_Employee_Salary_Error']);
        }
    }
    /////////////////////////////////////

    ?>
</body>

</html>