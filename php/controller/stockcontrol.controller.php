<?php
date_default_timezone_set("Asia/Manila");
class stockcontrolController extends StockControl
{
    private $boss;
    private $currentDate;

    private $ID;
    private $itemNo;
    private $itemName;
    private $itemDescription;
    private $price;
    private $quantity;
    private $amount;
    private $tax_rate;
    private $tax;
    private $total;

    # SET DATAS FUNCTIONS
    public function set_add_stockcontrol_data($itemNo, $itemName, $itemDescription, $price, $quantity, $amount, $tax_rate, $tax, $total)
    {
        $this->boss = $_SESSION['admin_name'];
        $this->currentDate = date("Y-m-d");
        $this->itemNo = $itemNo;
        $this->itemName = $itemName;
        $this->itemDescription = $itemDescription;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->amount = $amount;
        $this->tax_rate = $tax_rate;
        $this->tax = $tax;
        $this->total = $total;
    }

    public function set_delete_stockcontrol_data($id, $itemNo, $quantity)
    {
        $this->boss = $_SESSION['admin_name'];
        $this->itemNo = $itemNo;
        $this->ID = $id;
        $this->quantity = $quantity;
    }
    # END OF SET DATAS FUNCTIONS

    public function insert_add_stockcontrol_data()
    {
        $error = array();
        #ERROR HANDLER 1
        if ($this->is_it_overQuantity()) {
            $error['overQuantity'] = "Can't exceed Quantity";
        }
        #ERROR HANDLER 2
        if ($this->is_inputs_empty()) {
            $error['noInputs'] = "Missing input/s!";
        }
        #ERROR HANDLER 3
        if ($this->is_quantity_less_than_10()) {
            $error['inventoryQuantityNeedToRestock'] = $this->itemName . ": needs to be restocked!";
        }
        #ERROR HANDLER 4

        #ERROR HANDLER 5

        if ($error) {
            $_SESSION['stockcontrol_Create_Error'] = $error;
        }

        $this->deductInventoryQuantityBystockcontrolQuantity($this->boss, $this->itemNo, $this->quantity, $this->getQuantityOfInventoryItemNo($this->boss, $this->itemNo));
        $this->createstockcontrol($this->boss, $this->currentDate, $this->itemNo, $this->itemName, $this->itemDescription, $this->price, $this->quantity, $this->amount, $this->tax_rate, $this->tax, $this->total);
    }

    public function delete_stockcontrol()
    {
        $InventoryQuantity = $this->getQuantityOfInventoryItemNo($this->boss, $this->itemNo);
        $redoAddedInventoryQuantity = (int) $InventoryQuantity + (int) $this->quantity;
        $this->redoDeductionOfInventoryQuantity($this->boss, $this->itemNo, $redoAddedInventoryQuantity);
        $this->deletestockcontrolData($this->ID, $this->boss);
    }

    #INITIALIZING ERROR HANDLERS

    # 1
    private function is_it_overQuantity()
    {
        $this->getQuantityOfInventoryItemNo($this->boss, $this->itemNo); # INITIALIZE inventory's quantity

        return ($this->getQuantityOfInventoryItemNo($this->boss, $this->itemNo) < $this->quantity) ? true : false;
    }

    # 2
    private function is_inputs_empty()
    {
        if (empty($this->itemNo) or empty($this->itemName) or empty($this->itemDescription) or empty($this->price) or empty($this->quantity) or empty($this->amount) or empty($this->tax_rate) or empty($this->tax) or empty($this->total)) {
            return true;
        } else {
            return false;
        }
    }

    # 3
    private function is_quantity_less_than_10()
    {
        if ($this->getQuantityOfInventoryItemNo($this->boss, $this->itemNo) <= 10) {
            return true;
        } else {
            return false;
        }
    }
}
