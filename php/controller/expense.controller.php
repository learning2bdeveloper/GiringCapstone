<?php
date_default_timezone_set("Asia/Manila");
class ExpenseController extends Expense
{

    private $category;
    private $description;
    private $budgetAmount;
    private $expenseAmount;
    private $due_date;
    private $id;
    private $payee;

    // INITIALIZING THE INPUTS!

    public function set_add_expense_data($category, $budgetAmount, $description, $expenseAmount, $due, $payee)
    {
        $this->category = $category;
        $this->description = $description;
        $this->budgetAmount = $budgetAmount;
        $this->expenseAmount = $expenseAmount;
        if ($due == "") {
            $this->due_date = "N/A";
        } else {
            $this->due_date = $due;
            $this->payee = $payee;
        }

    }

    public function set_update_expense_data($id, $description, $categoryAmount)
    {
        $this->description = $description;
        $this->id = $id;
        $this->budgetAmount = $categoryAmount;
    }

    public function set_give_back_amount_after_deletion_of_expense_data_amount($id, $category, $categoryAmount)
    {
        $this->id = $id;
        $this->category = $category;
        $this->budgetAmount = $categoryAmount;
    }

    //SETTING UP AND CHECKING BEFORE ADDING TO THE DATABASE

    public function insert_add_expense_data()
    {
        $errors = array();

        if ($this->is_add_data_inputs_empty() == true) {
            $errors["data_inputs_are_empty"] = "The inputs are empty!";
        }

        if ($this->is_description_have_consecutive_spaces() == true) {
            $errors["description_have_consecutive_spaces"] = "Description should not have multiple spaces!";
        }

        if ($this->is_description_all_spaces() == true) {
            $errors["description_are_all_spaces"] = "Description are all spaces!";
        }

        if ($this->is_expenseAmount_input_valid() == true) {
            $errors["amount_input_valid"] = "Amount input numbers are only allowed!";
        }

        if ($this->is_description_input_valid() == true) {
            $errors["description_input_valid"] = "Description input Special Chars are not allowed!";
        }

        if ($this->is_expenseAmount_over_budgetAmount() == true) {
            $errors["overbudget"] = "OVERBUDGET!";
        }

        // if ($this->is_due_date_valid() == true) {
        //     $errors["due_date_not_valid"] = "DUE DATE NOT VALID!";
        // }

        if ($errors) {
            $_SESSION['expense_Create_Error'] = $errors;
            echo "expense.php";
            die();
        }

        $boss = $_SESSION["admin_name"];
        $currentDate = date("Y-m-d");

        $this->insert_add_data_to_database($boss, $currentDate, $this->category, $this->description, $this->expenseAmount, $this->budgetAmount, $this->due_date, $this->payee);
        $this->insert_add_data_to_database2($boss, $currentDate, $this->category, $this->description, $this->expenseAmount, $this->budgetAmount, $this->due_date, $this->payee);

        $newAmount = $this->budgetAmount - $this->expenseAmount;
        $this->insert_update_data_to_database_budget_minus_expense($boss, $this->category, $currentDate, $newAmount);
    }

    public function insert_update_expense_data()
    {
        $errors = array();

        if ($this->is_update_data_inputs_empty() == true) {
            $errors["data_inputs_are_empty"] = "The inputs are empty!";
        }

        if ($this->is_description_have_consecutive_spaces() == true) {
            $errors["description_have_consecutive_spaces"] = "Description should not have multiple spaces!";
        }

        if ($this->is_description_all_spaces() == true) {
            $errors["description_are_all_spaces"] = "Description are all spaces!";
        }

        if ($this->is_description_input_valid() == true) {
            $errors["description_input_valid"] = "Description input Special Chars are not allowed!";
        }

        if ($errors) {
            $_SESSION['expense_Update_Error'] = $errors;
            echo "expense.php";
            die();
        }

        $updateDate = date("Y-m-d");

        $this->insert_update_data_to_database($this->id, $updateDate, $this->description);
    }

    public function give_back_amount_after_deletion_of_expense_data_amount()
    {

        $boss = $_SESSION["admin_name"];
        $currentDate = date("Y-m-d");
        $newCategoryAmount = $this->budgetAmount;
        $this->add_amount_after_delete_expense_data($boss, $newCategoryAmount, $this->category, $currentDate);
        $this->delete_expense_data($this->id);
    }

    // ERROR HANDLERS FOR ADD & UPDATE FUNCTION

    private function is_description_input_valid()
    {
        //return (preg_match("/^[[a-zA-Z0-9 !?.&]*$/", $this->description)) ? true : false;
    }

    // private function is_due_date_valid()
    // {
    //     return (!preg_match("/^[[0-9-]*$/", $this->due_date)) ? true : false;
    // }

    private function is_expenseAmount_input_valid()
    {
        return (!preg_match("/^[[0-9]*$/", $this->expenseAmount)) ? true : false;
    }

    private function is_description_have_consecutive_spaces()
    {
        return (preg_match('/\s{2,}/', $this->description)) ? true : false;
    }

    private function is_description_all_spaces()
    {
        return (empty(trim($this->description))) ? true : false;
    }

    private function is_add_data_inputs_empty()
    {
        return (empty($this->category) or empty($this->description) or empty($this->expenseAmount)) ? true : false;
    }

    private function is_update_data_inputs_empty()
    {
        return (empty($this->description)) ? true : false;
    }

    private function is_expenseAmount_over_budgetAmount()
    {
        if (preg_match("/^[[0-9]*$/", $this->expenseAmount)) {
            return ($this->expenseAmount > $this->budgetAmount) ? true : false;
        }

    }

}
