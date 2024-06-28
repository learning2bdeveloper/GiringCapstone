<?php
date_default_timezone_set("Asia/Manila");

class SalaryController extends Salary
{

    ////////salary
    private $empID;
    private $pay_frequency;
    private $rate;
    private $dailyWorkHours;
    private $workDaysPerWeek;
    private $salary;
    private $Amount;

    public function set_Salary_employee_data($empID, $pay_frequency, $rate, $dailyWorkHours, $workDaysPerWeek)
    {
        $this->empID = $empID;
        $this->pay_frequency = $pay_frequency;
        $this->rate = $rate;
        $this->dailyWorkHours = $dailyWorkHours;
        $this->workDaysPerWeek = $workDaysPerWeek;
    }

    public function set_Update_Salary_data($empID, $pay_frequency, $rate, $dailyWorkHours, $workDaysPerWeek)
    {
        $this->empID = $empID;
        $this->pay_frequency = $pay_frequency;
        $this->rate = $rate;
        $this->dailyWorkHours = $dailyWorkHours;
        $this->workDaysPerWeek = $workDaysPerWeek;
    }

    public function set_Delete_Salary_data($empID)
    {
        $this->empID = $empID;
    }

    public function set_Salary_Payment_History_data($empID, $pay_frequency, $salary)
    {
        $this->empID = $empID;
        $this->pay_frequency = $pay_frequency;
        $this->salary = $salary;
    }

    public function create_Employee_Salary()
    { // done

        $errors = [];

        if ($this->is_salary_inputs_empty() == true) {
            $errors["inputsEmpty"] = "Input/s are empty!";
        }

        if ($errors) {

            $_SESSION['employee_Set_Employee_Salary_Error'] = $errors;

            header('Location: ../views/salaries.view/salary.php'); // done
            die();
        }
        $boss = $_SESSION['admin_name'];
        $taxDeuductedSalary = CalculateSalaryDeductedByTax($this->pay_frequency, $this->rate, $this->dailyWorkHours, $this->workDaysPerWeek);
        $currentDate = new DateTime();
        $formattedDate = $currentDate->format('Y/m/d g:i A');
        $next_Pay_DateFormattedDate = "";

        switch ($this->pay_frequency) {

            case 'Semi-Monthly':
                $currentDate = new DateTime();
                $currentDate->modify('+15 days'); // Moves to the middle of the next semi-month
                $next_Pay_DateFormattedDate = $currentDate->format('Y/m/d g:i A');
                break;
            default:
                $currentDate = new DateTime();
                $currentDate->modify('+1 month'); // Moves to the middle of the next semi-month
                $next_Pay_DateFormattedDate = $currentDate->format('Y/m/d g:i A');
        }

        $this->setSalary($formattedDate, $boss, $this->empID, $this->pay_frequency, $this->rate, $this->dailyWorkHours, $this->workDaysPerWeek, $taxDeuductedSalary, $next_Pay_DateFormattedDate);
    }

    public function add_Payment_Data_to_History()
    {

        $Amount = ""; // halin sa private after checking kung mas taas ang budget kay sa salary na e iban
        $errors = array();

        if ($this->is_employee_category_budget_is_not_exist() == true) {
            $errors["not_exist"] = "Employee Budget Category does not Exist!";
        }

        if ($this->is_employee_budget_not_good_enough() == true) {
            $errors["over_budget"] = "Over Budget!";
        }

        if ($errors) {
            $_SESSION['employee_Set_Employee_Salary_Error'] = $errors;

            echo "salary.php?error"; // done
            die();
        }
        $boss = $_SESSION['admin_name'];
        $currentDate = new DateTime();
        $currentFormattedDate = $currentDate->format('Y/m/d g:i A');
        $next_Pay_DateFormattedDate = "";
        switch ($this->pay_frequency) {

            case 'Semi-Monthly':
                $currentDate = new DateTime();
                $currentDate->modify('+15 days'); // Moves to the middle of the next semi-month
                $next_Pay_DateFormattedDate = $currentDate->format('Y/m/d g:i A');
                break;
            default:
                $currentDate = new DateTime();
                $currentDate->modify('+1 month'); // Moves to the middle of the next semi-month
                $next_Pay_DateFormattedDate = $currentDate->format('Y/m/d g:i A');
        }

        $this->add_to_history_payment($boss, $this->empID, $this->pay_frequency, $currentFormattedDate, $next_Pay_DateFormattedDate, $this->salary);
        $this->add_date_after_clicking_pay($boss, $this->empID, $next_Pay_DateFormattedDate);
        $finalDecreasedEmployeeBudgetData = ((int) $this->Amount - (int) $this->salary);
        $this->decrease_employee_category_budget($finalDecreasedEmployeeBudgetData, $boss); //diri butang na ma iban ang budget employee kulang
    }

    public function update_Employee_Salary()
    {

        $errors = [];

        if ($this->is_salary_update_inputs_empty() == true) {
            $errors["inputsEmpty"] = "Input/s are empty!";
        }

        if ($errors) {

            $_SESSION['employee_Update_Employee_Salary_Error'] = $errors;

            header('Location: ../views/salaries.view/salary.php'); // done
            die();
        }

        $taxDeuductedSalary = CalculateSalaryDeductedByTax($this->pay_frequency, $this->rate, $this->dailyWorkHours, $this->workDaysPerWeek);
        $next_Pay_DateFormattedDate = 0;

        switch ($this->pay_frequency) {

            case 'Semi-Monthly':
                $currentDate = new DateTime();
                $currentDate->modify('+15 days'); // Moves to the middle of the next semi-month
                $next_Pay_DateFormattedDate = $currentDate->format('Y/m/d g:i A');
                break;
            default:
                $currentDate = new DateTime();
                $currentDate->modify('+1 month'); // Moves to the middle of the next semi-month
                $next_Pay_DateFormattedDate = $currentDate->format('Y/m/d g:i A');
        }
        $boss = $_SESSION['admin_name'];
        $this->editSalary($next_Pay_DateFormattedDate, $taxDeuductedSalary, $boss, $this->empID, $this->pay_frequency, $this->rate, $this->dailyWorkHours, $this->workDaysPerWeek);
    }

    public function removeSalary()
    {
        $boss = $_SESSION['admin_name'];
        $this->deleteSalary($this->empID, $boss);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////

    private function is_employee_budget_not_good_enough()
    { // done
        $boss = $_SESSION['admin_name'];
        $budget = $this->get_employee_category_budget($boss);

        if ($budget !== false) {
            $budgetAmount = $budget->fetch();
            if ($budgetAmount['Amount'] < $this->salary) {
                return true;
            }
            if ($budgetAmount['Amount'] > $this->salary) { // masdako ang budget kay sa salary na e iban
                $this->Amount = $budgetAmount['Amount'];
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    private function is_salary_inputs_empty()
    {
        if (empty($this->empID) or empty($this->pay_frequency) or empty($this->rate) or empty($this->dailyWorkHours) or empty($this->workDaysPerWeek)) {
            return true;
        } else {
            return false;
        }
    }

    private function is_employee_category_budget_is_not_exist()
    { // done
        $boss = $_SESSION['admin_name'];
        if ($this->check_employee_category_budget_not_exist($boss) == true) {
            return true;
        } else {
            return false;
        }
    }

    // protected function is_exist_check_employee_salary_id($empID) {
    //     $query = "SELECT `empID_FK`FROM `salary` WHERE `empID_FK` = :employeeID";
    //     $stmt = $this->connect()->prepare($query);
    //     $stmt->bindParam('employeeID', $empID);

    //     if(!$stmt->execute()) {
    //         $stmt = null;
    //         header('Location: ../../employee.php?error=stmtfailed');
    //         exit();
    //     }

    //     if($stmt->rowCount() > 0) {
    //         return true;
    //     }else {
    //         return false;
    //     }
    // }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    private function is_salary_update_inputs_empty()
    { // done
        if (empty($this->pay_frequency) or empty($this->rate) or empty($this->dailyWorkHours) or empty($this->workDaysPerWeek)) {
            return true;
        } else {
            return false;
        }
    }
}
