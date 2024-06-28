<?php
    session_start();
    require '../classes/database.classes.php';
    require '../classes/employee.classes.php';
    require '../controller/employee.controller.php';
    require '../functions/empIDAutoGenerator.php';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if(isset($_POST['empAdd'])) { // para sa add button sa employee.php para mag add bag o employee.
    //halin ni sa employeeAddForm.php
 
    $empFirstName = ucwords($_POST['empFirstName']);
    $empLastName= ucfirst($_POST['empLastName']);
    $empCity = ucwords($_POST['empCity']);
    $empStreet = ucwords($_POST['empStreet']);
    $empBirthdate = $_POST['empBirthdate'];
    $empPhone = $_POST['empPhone'];
    $empPwd = $_POST['empPwd'];

    $employeeCon = new EmployeeController;
    $employeeCon->set_Create_employee_data($empFirstName, $empLastName, $empCity, $empStreet, $empBirthdate, $empPhone, $empPwd);
    //Running Error Handlers and employee 

    $employeeCon->createEmployee();

    header("location: ../views/employees.view/employee.php?error=none");
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if(isset($_POST['empUpdate'])) { // para sa button nga mag update sang info about sa employee sa employee.php table
    //halin ni sa employeeUpdateForm.php
  
    $empID = $_POST['empID'];
    $empFirstName = ucfirst($_POST['empFirstName']);
    $empLastName= ucfirst($_POST['empLastName']);
    $empCity = $_POST['empCity'];
    $empStreet = ucwords($_POST['empStreet']);
    $empBirthdate = $_POST['empBirthdate'];
    $empPhone = $_POST['empPhone'];
    $empPwd = $_POST['empPwd'];
    $currentID = $_POST['currentID'];
    $currentPhone = $_POST['currentPhone'];

    $employeeCon = new EmployeeController;
    $employeeCon->set_Update_employee_data($currentID, $currentPhone, $empID, $empFirstName, $empLastName, $empCity, $empStreet, $empBirthdate, $empPhone, $empPwd);

    $employeeCon->updateEmployee();

    echo "employee.php";
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if(isset($_POST['empDelete'])) { //para sa button nga delete sng specific employee sa employee.php table
    //halin ni sa employee.php sa delete na button
  
$empID = $_POST['empDelete']; // kwaon ang empID sa url

    $employee = new EmployeeController;

    $employee->set_Delete_employee_data($empID);
    $employee->removeEmployee();

    //Going back to front page
   header("location: ../views/employees.view/employee.php?employee=deleted");
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


