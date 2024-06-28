<?php

class StockControl extends Database
{
    protected function deductInventoryQuantityBystockcontrolQuantity($boss, $itemNo, $quantity, $inventoryQuantity)
    {
        $query = "UPDATE `inventory` SET `quantity` = :quantity WHERE `Owner_admin_name_FK`= :boss AND `item_no`= :itemNo;";

        $deductedQuantity = ((int) $inventoryQuantity - (int) $quantity);

        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam('boss', $boss);
        $stmt->bindParam('itemNo', $itemNo);
        $stmt->bindParam('quantity', $deductedQuantity);

        if (!$stmt->execute()) {
            header("Location: ../views/stockcontrol.view/stockcontrol.php?stmt=failed!");
            $stmt = null;
            die();
        }

    }
    protected function createstockcontrol($boss, $currentDate, $itemNo, $itemName, $itemDescription, $price, $quantity, $amount, $tax_rate, $tax, $total)
    {
        $query = "INSERT INTO `stockcontrol`(`Owner_admin_name_FK`, `date`, `item_no`, `item_name`, `item_description`, `price`, `quantity`, `amount`, `tax_rate`, `tax`, `total`) VALUES (:boss, :currentDate, :item_no, :item_name, :item_description, :price, :quantity, :amount, :tax_rate, :tax, :total)";

        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam('boss', $boss);
        $stmt->bindParam('currentDate', $currentDate);
        $stmt->bindParam('item_no', $itemNo);
        $stmt->bindParam('item_name', $itemName);
        $stmt->bindParam('item_description', $itemDescription);
        $stmt->bindParam('price', $price);
        $stmt->bindParam('quantity', $quantity);
        $stmt->bindParam('amount', $amount);
        $stmt->bindParam('tax_rate', $tax_rate);
        $stmt->bindParam('tax', $tax);
        $stmt->bindParam('total', $total);

        if (!$stmt->execute()) {
            header("Location: ../views/stockcontrol.view/stockcontrol.php?stmt=failed!");
            $stmt = null;
            die();
        }

    }

    # START PART OF DELETE
    protected function redoDeductionOfInventoryQuantity($boss, $itemNo, $finalRedoQuantity)
    {
        $query = "UPDATE `inventory` SET `quantity` = :quantity WHERE `Owner_admin_name_FK` = :boss AND `item_no` = :item_no";

        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':quantity', $finalRedoQuantity);
        $stmt->bindParam(':boss', $boss);
        $stmt->bindParam(':item_no', $itemNo);

        if (!$stmt->execute()) {
            echo "stockcontrol.php?stmt=failed";
            $stmt = null;
            die();
        }

    }

    protected function deletestockcontrolData($id, $boss)
    {
        $query = "DELETE FROM `stockcontrol` WHERE `ID` = :id AND `Owner_admin_name_FK` = :boss";

        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam('boss', $boss);
        $stmt->bindParam('id', $id);

        if (!$stmt->execute()) {
            echo "stockcontrol.php?stmt=failed";
            $stmt = null;
            die();
        }
    }
    # END OF PART OF DELETE

    # START OF PUBLIC FUNCTIONS
    public function getQuantityOfInventoryItemNo($boss, $itemNo)
    {
        $query = "SELECT `quantity` FROM `inventory` WHERE  `Owner_admin_name_FK` = :boss AND `item_no` = :item_no;";

        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam('boss', $boss);
        $stmt->bindParam('item_no', $itemNo);

        if (!$stmt->execute()) {
            header("Location: ../views/stockcontrol.view/stockcontrol.php?stmt=failed!");
            $stmt = null;
            die();
        }

        $fetch = $stmt->fetch();

        return $fetch['quantity'];
    }

    public function search_stockcontrol_table($input, $boss)
    {
        try {
            $query = "SELECT * FROM `stockcontrol` WHERE `Owner_admin_name_FK` = :boss AND (`empID_FK` LIKE :input
        OR `date` LIKE :input OR `item_no` LIKE :input OR `item_name` LIKE :input OR `item_description` LIKE :input OR `price` LIKE :input OR `quantity` LIKE :input OR `amount` LIKE :input OR `tax_rate` LIKE :input OR `tax` LIKE :input OR `total` LIKE :input OR `modified_date` LIKE :input)";
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

    # END OF PUBLIC FUNCTIONS
}
