<?php

class Salary extends Database
{

    protected function setSalary($current_date, $current_admin, $empID, $get_paid, $salary, $hrs_per_day, $days_per_week, $taxReducedSalary, $next_pay_date)
    {
        $query = "INSERT INTO `salary`(`date_created`, `admin_name_FK`, `empID_FK`, `pay_frequency`, `rate`, `daily_work_hours`, `work_days_per_week`, `salary`, `pay_date`) VALUES (:current_date, :boss, :empID, :pay_frequency, :rate, :daily_work_hours, :work_days_per_week, :salary, :pay_date)";

        $stmt = $this->connect()->prepare($query);

        $stmt->bindParam('current_date', $current_date);
        $stmt->bindParam('boss', $current_admin);
        $stmt->bindParam('empID', $empID);
        $stmt->bindParam('pay_frequency', $get_paid);
        $stmt->bindParam('rate', $salary);
        $stmt->bindParam('daily_work_hours', $hrs_per_day);
        $stmt->bindParam('work_days_per_week', $days_per_week);
        $stmt->bindParam('salary', $taxReducedSalary);
        $stmt->bindParam('pay_date', $next_pay_date);

        if (!$stmt->execute()) {
            $stmt = null;
            header('Location: ../views/salary.view/salary.php');
            exit();
        }

    }

    protected function editSalary($next_Pay_DateFormattedDate, $taxDeuductedSalary, $boss, $empID, $pay_frequency, $rate, $dailyWorkHours, $workDaysPerWeek)
    {
        $query = "UPDATE `salary` SET `salary`= :salary, `pay_date`= :pay_date,`pay_frequency`= :pay_frequency, `rate`= :rate, `daily_work_hours`= :dailyWorkHours, `work_days_per_week`= :workDaysPerWeek WHERE `admin_name_FK` = :boss AND `empID_FK` = :empID";
        $stmt = $this->connect()->prepare($query);

        $stmt->bindParam('empID', $empID);
        $stmt->bindParam('salary', $taxDeuductedSalary);
        $stmt->bindParam('pay_date', $next_Pay_DateFormattedDate);
        $stmt->bindParam('boss', $boss);
        $stmt->bindParam('pay_frequency', $pay_frequency);
        $stmt->bindParam('rate', $rate);
        $stmt->bindParam('dailyWorkHours', $dailyWorkHours);
        $stmt->bindParam('workDaysPerWeek', $workDaysPerWeek);

        if (!$stmt->execute()) {
            $stmt = null;
            header('Location: ../salaries.views/salary.php?error=editfailed');
            exit();
        }
    }

    protected function deleteSalary($empID, $boss)
    {
        $query = "DELETE FROM `salary` WHERE `empID_FK`= :empID AND `admin_name_FK` = :boss;";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam('empID', $empID);
        $stmt->bindParam('boss', $boss);

        if (!$stmt->execute()) {
            $stmt = null;
            echo "salary.php?deletefailed";
            exit();
        }
    }

    protected function add_date_after_clicking_pay($boss, $empID, $input)
    {
        $query = "UPDATE `salary` SET `pay_date`= :pay_date WHERE `admin_name_FK` = :boss AND `empID_FK` = :empID";

        $stmt = $this->connect()->prepare($query);

        $stmt->bindParam('pay_date', $input);
        $stmt->bindParam('boss', $boss);
        $stmt->bindParam('empID', $empID);

        if (!$stmt->execute()) {
            $stmt = null;
            echo "salary.php?payfailed";
            exit();
        }

    }

    protected function add_to_history_payment($boss, $empID, $pay_frequency, $current_date, $next_pay_date, $salary)
    {
        $query = "INSERT INTO `salary_history`(`Owner_admin_name_FK`, `empID_FK`, `pay_frequency`, `paid_date`, `next_paid_date`, `salary`) VALUES (:Owner_admin_name_FK, :empID_FK, :pay_frequency, :paid_date, :next_paid_date, :salary)";

        $stmt = $this->connect()->prepare($query);

        $stmt->bindParam('Owner_admin_name_FK', $boss);
        $stmt->bindParam('empID_FK', $empID);
        $stmt->bindParam('pay_frequency', $pay_frequency);
        $stmt->bindParam('paid_date', $current_date);
        $stmt->bindParam('next_paid_date', $next_pay_date);
        $stmt->bindParam('salary', $salary);

        if (!$stmt->execute()) {
            $stmt = null;
            echo "salary.php?errorstmt";
            exit();
        }
    }

    protected function decrease_employee_category_budget($input, $boss)
    {
        $query = "UPDATE `budget` SET `Amount`= :Amount WHERE `Owner_admin_name_FK`= :boss AND `Category`= 'Employees';";

        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam('boss', $boss);
        $stmt->bindParam('Amount', $input);

        if (!$stmt->execute()) {
            $stmt = null;
            echo "salary.php?errorstmt";
            exit();
        }
    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function deletePaymentHistoryData($ID, $boss)
    {
        $query = "DELETE FROM `salary_history` WHERE `ID`= :ID AND `Owner_admin_name_FK` = :boss;";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam('ID', $ID);
        $stmt->bindParam('boss', $boss);

        if (!$stmt->execute()) {
            $stmt = null;
            echo "salary.php?deletefailed";
            exit();
        }
    }

    public function check_employee_category_budget_not_exist($boss)
    {
        $query = "SELECT * FROM `budget` WHERE `Owner_admin_name_FK` = :boss AND `Category` = 'Employees';";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam('boss', $boss);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_employee_category_budget($boss)
    {
        $query = "SELECT `Amount` FROM `budget` WHERE `Owner_admin_name_FK` = :boss AND `Category` = 'Employees';";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam('boss', $boss);

        if (!$stmt->execute()) {
            $stmt = null;
            echo "salary.php?deletefailed";
            exit();
        }

        if ($stmt->rowCount() > 0) {
            return $stmt;
        } else {
            return false;
        }

    }

    public function show_salary_history_table($boss)
    {
        try {
            $query = "SELECT * FROM `salary_history` WHERE `Owner_admin_name_FK` = :boss ORDER BY `ID` DESC;";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam('boss', $boss);
            $stmt->execute();
            return $stmt;
            $stmt = null;
        } catch (ErrorException $e) {
            echo $e->getMessage();
        }
    }

    public function show_salary_due_today_table($boss)
    {
        try {
            $query = "SELECT * FROM `salary_history` WHERE `Owner_admin_name_FK` = :boss;";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam('boss', $boss);
            $stmt->execute();
            return $stmt;
            $stmt = null;
        } catch (ErrorException $e) {
            echo $e->getMessage();
        }
    }

    public function search_salary_table($input, $boss)
    {
        try {
            $query = "SELECT * FROM `salary` WHERE `admin_name_FK` = :boss AND (`empID_FK` LIKE :input
    OR `pay_frequency` LIKE :input OR `rate` LIKE :input OR `daily_work_hours` LIKE :input OR `work_days_per_week` LIKE :input OR `salary` LIKE :input OR `date_created` LIKE :input OR `pay_date` LIKE :input)";
            $stmt = $this->connect()->prepare($query);
            $input .= '%';
            $stmt->bindParam('input', $input);
            $stmt->bindParam('boss', $boss);
            $stmt->execute();
            return $stmt;
        } catch (ErrorException $e) {
            echo $e->getMessage();
        }
    }

    public function get_admin_employees_salary_data($admin)
    {
        $query = "SELECT `empID_FK`, `pay_frequency`, `rate`, `daily_work_hours`, `work_days_per_week`, `#_of_Holidays` FROM `salary` WHERE `admin_name_FK` = :adminName;";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam('adminName', $admin);
        $stmt->execute();

        if (!$stmt->execute()) {
            $stmt = null;
            header('Location: ../../employee.php?error=stmtfailed');
            exit();
        }

        return $stmt;
    }

    public function get_employees_salary_data($empID)
    { // no need mag butang admin kay nd man daan pwede maka same empID kay PK
        $query = "SELECT * FROM `salary` WHERE `empID_FK` = :empID";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam('empID', $empID);
        $stmt->execute();

        if (!$stmt->execute()) {
            $stmt = null;
            header('Location: ../views/employees.view/employeeSalary.php?error=stmtfailed');
            exit();
        }

        return $stmt;
    }

    public function get_employees_id($admin)
    {

        // $query = "SELECT `empID` FROM `employee` WHERE `Owner_admin_name_FK` = :adminName;";
        $query = "SELECT e.`empID` FROM `employee` e LEFT JOIN `salary` s ON e.`empID` = s.`empID_FK`WHERE e.`Owner_admin_name_FK` = :adminName AND s.`empID_FK` IS NULL;"; // kwaon lng ang employee ID from employee table nga wla pa na butangan salary or ga exist sa salary table na employee ID.

        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam('adminName', $admin);
        $stmt->execute();

        if (!$stmt->execute()) {
            $stmt = null;
            header('Location: ../../employee.php?error=stmtfailed');
            exit();
        }

        return $stmt;
    }

}
