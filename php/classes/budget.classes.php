<?php

class Budget extends Database
{

    // PROTECTED FUNCTIONS
    protected function insert_add_data_to_database($boss, $date, $category, $description, $amount)
    {
        $query = "INSERT INTO `budget`(`Owner_admin_name_FK`, `Date`, `Category`, `Description`, `Amount`) VALUES (:boss, :currentDate, :category, :descriptions, :amount)";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':boss', $boss);
        $stmt->bindParam(':currentDate', $date);
        $stmt->bindParam('category', $category);
        $stmt->bindParam('descriptions', $description);
        $stmt->bindParam('amount', $amount);

        if (!$stmt->execute()) {
            $stmt = null;
            echo 'budget.php?error=failedToConnect';
            exit();
        }
        $stmt = null;
    }

    protected function insert_update_data_to_database($id, $date, $category, $description, $amount)
    {
        $query = "UPDATE `budget` SET `Category`= :category, `Description`= :descriptions, `Amount`= :amount ,`Update_date`= :updateDate WHERE `ID` = :id";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam('id', $id);
        $stmt->bindParam(':updateDate', $date);
        $stmt->bindParam('category', $category);
        $stmt->bindParam('descriptions', $description);
        $stmt->bindParam('amount', $amount);

        if (!$stmt->execute()) {
            $stmt = null;
            echo 'budget.php?error=failedToConnect';
            exit();
        }
        $stmt = null;
    }

    // PUBLIC FUNCTIONS

    public function check_update_input_category_already_exist($category, $boss)
    {
        try {
            $query = "SELECT `Category` FROM `budget` WHERE `Category` = :category AND `Owner_admin_name_FK` = :boss";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam('category', $category);
            $stmt->bindParam('boss', $boss);
            $stmt->execute();

            return ($stmt->rowCount() > 0) ? true : false;

        } catch (ErrorException $e) {
            echo $e->getMessage();
        }
    }

    public function check_add_input_category_already_exist($category, $boss)
    {
        try {
            $query = "SELECT `Category` FROM `budget` WHERE `Category` = :category AND `Owner_admin_name_FK` = :boss";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam('category', $category);
            $stmt->bindParam('boss', $boss);
            $stmt->execute();

            return ($stmt->rowCount() > 0) ? true : false;

        } catch (ErrorException $e) {
            echo $e->getMessage();
        }
    }

    public function delete_budget_data($id)
    {
        try {
            $query = "DELETE FROM `budget` WHERE `ID` = :id";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam('id', $id);
            $stmt->execute();
        } catch (ErrorException $e) {
            echo $e->getMessage();
        }
    }

    public function search_budget_table($input, $boss)
    {
        try {
            $query = "SELECT * FROM `budget` WHERE  `Owner_admin_name_FK` = :boss AND (`Date` LIKE :input  OR `Category` LIKE :input OR `Description` LIKE :input OR `Amount` LIKE :input)";
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

  

    public function show_budget_data_by_id($id)
    {
        try {
            $query = "SELECT * FROM `budget` WHERE `ID` = :id;";
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
