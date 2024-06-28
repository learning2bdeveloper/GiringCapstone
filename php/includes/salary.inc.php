<?php
    session_start();

    require '../classes/database.classes.php';
    require '../classes/salary.classes.php';
    require '../controller/salary.controller.php';
    require '../functions/salaryTaxCalculations.functions.php';

    if(isset($_POST['salaryAdd'])) { //para sa button nga setsalary sa kada employee sa employee.php table // 
        //halin ni sa employeeAddSalaryForm.php sa button na salary 
    
        $empID                  = $_POST['empIDs'];
        $pay_frequency          = $_POST['payFrequency'];
        $rate                   = $_POST['rate'];
        $dailyWorkHours         = $_POST['dailyWorkHours'];
        $workDaysPerWeek        = $_POST['workDaysPerWeek'];
    
        $employeeCon = new SalaryController;
        $employeeCon->set_Salary_employee_data($empID, $pay_frequency, $rate, $dailyWorkHours, $workDaysPerWeek);
        $employeeCon->create_Employee_Salary();
        
        header("location: ../views/salaries.view/salary.php?salary=Setted"); // done
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if(isset($_POST['salaryUpdate'])) {

        $empID                  = $_POST['salaryUpdate'];
        $pay_frequency          = $_POST['payFrequency'];
        $rate                   = $_POST['rate'];
        $dailyWorkHours         = $_POST['dailyWorkHours'];
        $workDaysPerWeek        = $_POST['workDaysPerWeek'];
    
        $employeeCon = new SalaryController;
        $employeeCon->set_Update_Salary_data($empID, $pay_frequency, $rate, $dailyWorkHours, $workDaysPerWeek);
        $employeeCon->update_Employee_Salary();
    
        header("location: ../views/salaries.view/salary.php?salary=Updated");// done
    
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if(isset($_POST['deleteSalary'])) {
        //Grabbing the data
        $empID = $_POST['deleteSalary'];
        
        $employee = new SalaryController;
        $employee->set_Delete_Salary_data($empID);
        $employee->removeSalary();
        
        echo "salary.php?salaryDeleted"; // done
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////
    if(isset($_POST["paid"])) {
        $empID                  = $_POST['empId'];
        $pay_frequency          = $_POST['pay_frequency'];
        $salary                 = $_POST['salary'];

        $SalaryController = new SalaryController;
        $SalaryController->set_Salary_Payment_History_data($empID, $pay_frequency, $salary);
        $SalaryController->add_Payment_Data_to_History();

        echo "salary.php?Salary=Paid";
    }

    if(isset($_POST["deleteHistorySalaryData"])) {
        $ID                  = $_POST['ID'];
        $currentAdmin        = $_POST['currentAdmin'];

        $Salary = new Salary;
        $Salary->deletePaymentHistoryData($ID, $currentAdmin);

        echo "salary.php?HistorySalaryDeleted!";
    }

    