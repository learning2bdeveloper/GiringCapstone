<?php

class Inventory extends Database
{

    protected function createInventory($boss, $currentDate, $itemNo, $itemName, $itemDescription, $quantity, $price, $amount)
    {
        $query = "INSERT INTO `inventory`(`Owner_admin_name_FK`, `date`, `item_no`, `item_name`, `item_description`, `quantity`, `price`, `amount`) VALUES (:boss, :currentDate, :item_no, :item_name, :item_description, :quantity, :price, :amount)";

        try {
            $stmt = $this->connect()->prepare($query);

            $stmt->bindParam('boss', $boss);
            $stmt->bindParam('currentDate', $currentDate);
            $stmt->bindParam('item_no', $itemNo);
            $stmt->bindParam('item_name', $itemName);
            $stmt->bindParam('item_description', $itemDescription);
            $stmt->bindParam('quantity', $quantity);
            $stmt->bindParam('price', $price);
            $stmt->bindParam('amount', $amount);

            if (!$stmt->execute()) {
                $stmt = null;
                header("location: ../views/inventory.view/inventory.php?error=stmtfailed");
                exit();
            }
            $stmt = null;

        } catch (ErrorException $e) {
            echo "Error Message: " . $e->getMessage();
        }
    }

    protected function updateInventory($boss, $id, $itemNo, $itemName, $itemDescription, $quantity, $amount)
    {
        $query = "UPDATE `inventory` SET `item_no`= :item_no, `item_name`= :item_name, `item_description`= :item_description, `quantity`= :quantity, `amount`= :amount  WHERE `ID` = :ID AND `Owner_admin_name_FK` = :Owner_admin_name_FK;";

        $stmt = $this->connect()->prepare($query);

        $stmt->bindParam('Owner_admin_name_FK', $boss);
        $stmt->bindParam('ID', $id);
        $stmt->bindParam('item_no', $itemNo);
        $stmt->bindParam('item_name', $itemName);
        $stmt->bindParam('item_description', $itemDescription);
        $stmt->bindParam('quantity', $quantity);
        $stmt->bindParam('amount', $amount);

        if (!$stmt->execute()) {
            $stmt = null;
            //header("location: ../views/inventory.view/inventory.php?error=stmtfailed");
            exit();
        }
    }

    protected function decrease_inventory_category_budget($input, $boss)
    {
        $query = "UPDATE `budget` SET `Amount`= :Amount WHERE `Owner_admin_name_FK`= :boss AND `Category`= 'Inventory';";

        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam('boss', $boss);
        $stmt->bindParam('Amount', $input);

        if (!$stmt->execute()) {
            $stmt = null;
            header("Location: ../views/inventory.view/inventory.php?error=stmtFailed");
            die();
        }
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////

    //  para mag check if ga exist ang inventory na budget
    public function check_inventory_category_budget_not_exist($boss)
    {
        $query = "SELECT * FROM `budget` WHERE `Owner_admin_name_FK` = :boss AND `Category` = 'Inventory';";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam('boss', $boss);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_inventory_category_budget($boss)
    {
        $query = "SELECT `Amount` FROM `budget` WHERE `Owner_admin_name_FK` = :boss AND `Category` = 'Inventory';";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam('boss', $boss);

        if (!$stmt->execute()) {
            $stmt = null;
            echo "inventory.php?deletefailed";
            exit();
        }

        if ($stmt->rowCount() > 0) {
            return $stmt;
        } else {
            return false;
        }

    }
    /////

    public function get_item_name_and_item_description($boss, $item_no)
    { // para sa revenue.php
        try {
            $query = "SELECT `item_name`, `item_description`, `price`, `quantity` FROM `inventory` WHERE `Owner_admin_name_FK` = :Owner_admin_name_FK AND `item_no` = :item_no";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam('Owner_admin_name_FK', $boss);
            $stmt->bindParam('item_no', $item_no);
            $stmt->execute();
            return $stmt;
        } catch (ErrorException $e) {
            echo $e->getMessage();
        }
    }

    public function get_item_no($boss)
    { // para sa revenue.php
        try {
            $query = "SELECT `item_no`FROM `inventory` WHERE `Owner_admin_name_FK` = :Owner_admin_name_FK";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam('Owner_admin_name_FK', $boss);
            $stmt->execute();
            return $stmt;
        } catch (ErrorException $e) {
            echo $e->getMessage();
        }
    }

    public function check_item_no_if_exist($boss, $item_no)
    {
        try {
            $query = "SELECT * FROM `inventory` WHERE `Owner_admin_name_FK` = :Owner_admin_name_FK AND `item_no` = :item_no";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam('Owner_admin_name_FK', $boss);
            $stmt->bindParam('item_no', $item_no);
            $stmt->execute();

            return ($stmt->rowCount() > 0) ? true : false;
        } catch (ErrorException $e) {
            echo $e->getMessage();
        }
    }

    public function check_item_name_if_exist($boss, $item_name)
    {
        try {
            $query = "SELECT * FROM `inventory` WHERE `Owner_admin_name_FK` = :Owner_admin_name_FK AND `item_name` = :item_name";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam('Owner_admin_name_FK', $boss);
            $stmt->bindParam('item_name', $item_name);
            $stmt->execute();

            return ($stmt->rowCount() > 0) ? true : false;
        } catch (ErrorException $e) {
            echo $e->getMessage();
        }
    }

    public function check_item_no_and_item_name_of_user_in_the_database($boss, $item_name)
    {
        try {
            $query = "SELECT * FROM `inventory` WHERE `Owner_admin_name_FK` = :Owner_admin_name_FK AND `item_name` = :item_name";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam('Owner_admin_name_FK', $boss);
            $stmt->bindParam('item_name', $item_name);
            $stmt->execute();
            return ($stmt->rowCount() > 0) ? true : false;

        } catch (ErrorException $e) {
            echo $e->getMessage();
        }
    }

    public function search_inventory_table($input, $boss)
    {
        try {
            $query = "SELECT * FROM `inventory` WHERE `Owner_admin_name_FK` = :boss AND (`empID_FK` LIKE :input
        OR `date` LIKE :input OR `item_no` LIKE :input OR `item_name` LIKE :input OR `item_description` LIKE :input OR `quantity` LIKE :input OR `amount` LIKE :input OR `modified_date` LIKE :input)";
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

    public function detele_id_inventory($id, $boss)
    { //para mag delete inventory
        $query = "DELETE FROM `inventory` WHERE `ID` = :id AND `Owner_admin_name_FK` = :boss";
        $stmt = $this->connect()->prepare($query);

        try {
            $stmt->bindParam('id', $id);
            $stmt->bindParam('boss', $boss);

            if (!$stmt->execute()) {
                $stmt = null;
                echo "inventory.php";
                exit();
            }
            return $stmt;

        } catch (ErrorException $e) {
            echo "Error Message: " . $e->getMessage();
        }
    }

    public function get_data_by_id($id)
    {
        $query = "SELECT `ID`, `item_no`, `item_name`, `item_description`, `quantity`, `amount` FROM `inventory` WHERE `ID` = :id";
        $stmt = $this->connect()->prepare($query);

        try {
            $stmt->bindParam('id', $id);

            if (!$stmt->execute()) {
                $stmt = null;
                header("location: ../views/inventory.view/inventory.php?error=stmtfailed");
                exit();
            }
            return $stmt;

        } catch (ErrorException $e) {
            echo "Error Message: " . $e->getMessage();
        }
    }

    public function redoDeductionInventoryBudget($boss, $addedAmount)
    {
        $query = "UPDATE `budget` SET `Amount` = :Amount WHERE `Category` = 'Inventory' AND `Owner_admin_name_FK` = :boss";

        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':boss', $boss);
        $stmt->bindParam(':Amount', $addedAmount);

        if (!$stmt->execute()) {
            $stmt = null;
            echo "inventory.php?stmt=failed";
            exit();
        }

        return $stmt;
    }

    protected function insertToExpenseInventory($boss, $currentDate, $categoryAmount, $item_description, $amount)
    {
        $query = "INSERT INTO `expense`(`Owner_admin_name_FK`, `Date`, `Category`, `Category_amount`, `Description`, `Amount`) VALUES (:boss, :currentDate, 'Inventory', :Category_amount, :item_description, :Amount);";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':boss', $boss);
        $stmt->bindParam(':currentDate', $currentDate);
        $stmt->bindParam(':Category_amount', $categoryAmount);
        $stmt->bindParam(':item_description', $item_description);
        $stmt->bindParam(':Amount', $amount);

        if (!$stmt->execute()) {
            $stmt = null;
            header("Location: ../views/inventory.view/inventory.php?error=stmtFailed");
            die();
        }
    }

}
