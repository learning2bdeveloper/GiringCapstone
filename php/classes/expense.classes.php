<?php

class Expense extends Database
{

    // PROTECTED FUNCTIONS
    protected function insert_add_data_to_database($boss, $currentDate, $category, $description, $expenseAmount, $budgetAmount, $due_date, $payee)
    {
        $query = "INSERT INTO `active_expense`(`Owner_admin_name_FK`, `Date`, `Due`, `Payee`, `Category`, `Category_amount`, `Description`, `Amount`) VALUES (:boss, :currentDate, :due, :payee, :category, :Category_amount, :descriptions, :amount)";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':boss', $boss);
        $stmt->bindParam(':currentDate', $currentDate);
        $stmt->bindParam('category', $category);
        $stmt->bindParam('Category_amount', $budgetAmount);
        $stmt->bindParam('descriptions', $description);
        $stmt->bindParam('amount', $expenseAmount);
        $stmt->bindParam('due', $due_date);
        $stmt->bindParam('payee', $payee);

        if (!$stmt->execute()) {
            $stmt = null;
            echo 'expense.php?error=failedToConnect';
            exit();
        }
        $stmt = null;
    }

    protected function insert_add_data_to_database2($boss, $currentDate, $category, $description, $expenseAmount, $budgetAmount, $due_date, $payee)
    {
        $query = "INSERT INTO `expense`(`Owner_admin_name_FK`, `Date`, `Due`, `Payee`, `Category`, `Category_amount`, `Description`, `Amount`) VALUES (:boss, :currentDate, :due, :payee, :category, :Category_amount, :descriptions, :amount)";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':boss', $boss);
        $stmt->bindParam(':currentDate', $currentDate);
        $stmt->bindParam('category', $category);
        $stmt->bindParam('Category_amount', $budgetAmount);
        $stmt->bindParam('descriptions', $description);
        $stmt->bindParam('amount', $expenseAmount);
        $stmt->bindParam('due', $due_date);
        $stmt->bindParam('payee', $payee);

        if (!$stmt->execute()) {
            $stmt = null;
            echo 'expense.php?error=failedToConnect';
            exit();
        }
        $stmt = null;
    }

    protected function insert_update_data_to_database($id, $date, $description)
    {
        $query = "UPDATE `active_expense` SET `Description`= :descriptions, `Update_date`= :updateDate WHERE `ID` = :id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam('id', $id);
        $stmt->bindParam(':updateDate', $date);
        $stmt->bindParam('descriptions', $description);

        if (!$stmt->execute()) {
            $stmt = null;
            echo 'expense.php?error=failedToConnect';
            exit();
        }
        $stmt = null;
    }

    protected function insert_update_data_to_database_budget_minus_expense($boss, $Category, $date, $amount)
    { // para mag iban kung mag add or update sa expense ang budget
        $query = "UPDATE `budget` SET `Amount`= :amount, `Update_date`= :updateDate WHERE `Category` = :Category AND `Owner_admin_name_FK` = :Owner_admin_name_FK";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam('Owner_admin_name_FK', $boss);
        $stmt->bindParam('Category', $Category);
        $stmt->bindParam(':updateDate', $date);
        $stmt->bindParam('amount', $amount);

        if (!$stmt->execute()) {
            $stmt = null;
            echo 'expense.php?error=failedToConnect';
            exit();
        }
        $stmt = null;
    }

    protected function delete_expense_data($id)
    {
        try {
            $query = "DELETE FROM `active_expense` WHERE `ID` = :id";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam('id', $id);
            $stmt->execute();
        } catch (ErrorException $e) {
            echo $e->getMessage();
        }
    }

    protected function add_amount_after_delete_expense_data($boss, $newCategoryAmount, $category, $updateDate)
    {
        try {
            $query = "UPDATE `budget` SET `Amount`= :amount, `Update_date`= :updateDate WHERE `Owner_admin_name_FK` = :Owner_admin_name_FK AND `Category` = :Category";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam('Owner_admin_name_FK', $boss);
            $stmt->bindParam('amount', $newCategoryAmount);
            $stmt->bindParam('Category', $category);
            $stmt->bindParam('updateDate', $updateDate);
            $stmt->execute();
        } catch (ErrorException $e) {
            echo $e->getMessage();
        }
    }
    // PUBLIC FUNCTIONS

    public function search_expense_table($input, $boss)
    {
        try {
            $query = "SELECT * FROM `active_expense` WHERE `Owner_admin_name_FK` = :boss AND (`Date` LIKE :input OR `Payee` LIKE :input OR`Due` LIKE :input OR `empID_FK` LIKE :input
        OR `Category` LIKE :input OR `Category_amount` LIKE :input OR `Description` LIKE :input OR `Update_date` LIKE :input OR `Amount` LIKE :input)";
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

    public function show_expense_data_by_id($id)
    {
        try {
            $query = "SELECT * FROM `expense` WHERE `ID` = :id;";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt;
            $stmt = null;
        } catch (ErrorException $e) {
            echo $e->getMessage();
        }
    }

    // for expense AddForm.php para ma kwa ang category
    public function getCategories($boss)
    {
        try {
            $query = "SELECT `Category` FROM `budget` WHERE `Owner_admin_name_FK` = :boss;";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam(':boss', $boss);
            $stmt->execute();
            return $stmt;
            $stmt = null;
        } catch (ErrorException $e) {
            echo $e->getMessage();
        }
    }

    public function getAmount($boss, $category)
    {
        try {
            $query = "SELECT `Amount` FROM `budget` WHERE `Owner_admin_name_FK` = :boss AND `Category` = :Category;";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam(':boss', $boss);
            $stmt->bindParam(':Category', $category);
            $stmt->execute();
            return $stmt;
            $stmt = null;
        } catch (ErrorException $e) {
            echo $e->getMessage();
        }
    }
}
