<?php
date_default_timezone_set("Asia/Manila");
class BudgetController extends Budget{
    
    private $currentCategory;
    private $category;
    private $description;
    private $amount;
    private $id;

    // INITIALIZING THE INPUTS!

    public function set_add_budget_data($category, $description, $amount) {
        $this->category = $category;
        $this->description = $description;
        $this->amount = $amount;
    }

    public function set_update_budget_data($id, $category, $description, $amount, $currentCategory) {
        $this->currentCategory = $currentCategory;
        $this->category = $category;
        $this->description = $description;
        $this->amount = $amount;
        $this->id = $id;
    }

    //SETTING UP AND CHECKING BEFORE ADDING TO THE DATABASE

    public function insert_add_budget_data() {
        $errors = array();

        if($this->is_add_data_inputs_empty() == true) {
            $errors["data_inputs_are_empty"] = "The inputs are empty!";
        }

        if($this->is_description_have_consecutive_spaces() == true) {
            $errors["description_have_consecutive_spaces"] = "Description should not have multiple spaces!";
        }
        
        if($this->is_description_all_spaces() == true) {
            $errors["description_are_all_spaces"] = "Description are all spaces!";
        }
        
        if($this->is_category_input_valid() == true) {
            $errors["category_input_valid"] = "Category input letters and numbers are only allowed!";
        }

        if($this->is_amount_input_valid() == true) {
            $errors["amount_input_valid"] = "Amount input numbers are only allowed!";
        }

        if($this->is_description_input_valid() == true) {
            $errors["description_input_valid"] = "Description input Special Chars are not allowed!";
        }

        if($this->category_already_exist() == true) {
            $errors["category_already_exist"] = "Category already exist!";
        }

        if($errors) {
            $_SESSION['budget_Create_Error'] = $errors;
            echo "budget.php";
            die();
        }
        
        $boss = $_SESSION["admin_name"];
        $currentDate = date("Y-m-d");

        $this->insert_add_data_to_database($boss, $currentDate, $this->category, $this->description, $this->amount);
    }

    public function insert_update_budget_data() {
        $errors = array();

        if($this->is_add_data_inputs_empty() == true) {
            $errors["data_inputs_are_empty"] = "The inputs are empty!";
        }

        if($this->is_description_have_consecutive_spaces() == true) {
            $errors["description_have_consecutive_spaces"] = "Description should not have multiple spaces!";
        }
        
        if($this->is_description_all_spaces() == true) {
            $errors["description_are_all_spaces"] = "Description are all spaces!";
        }
        
        if($this->is_category_input_valid() == true) {
            $errors["category_input_valid"] = "Category input letters and numbers are only allowed!";
        }

        if($this->is_amount_input_valid() == true) {
            $errors["amount_input_valid"] = "Amount input numbers are only allowed!";
        }

        if($this->is_description_input_valid() == true) {
            $errors["description_input_valid"] = "Description input Special Chars are not allowed!";
        }

        if($this->category_update_already_exist() == true) {
            $errors["category_already_exist"] = "Category already exist!";
        }

        if($errors) {
            $_SESSION['budget_Update_Error'] = $errors;
            echo "budget.php";
            die();
        }
        
        $updateDate = date("Y-m-d");

        $this->insert_update_data_to_database($this->id, $updateDate, $this->category, $this->description, $this->amount);
    }
    
    // ERROR HANDLERS FOR ADD & UPDATE FUNCTION 

    private function category_update_already_exist() {
        if($this->category == $this->currentCategory) {
            return false;
        }
        if($this->check_update_input_category_already_exist($this->category, $_SESSION["admin_name"]) == true) {
            return true;
        }else {
            return false;
        }
    }

    private function category_already_exist() {
        return ($this->check_add_input_category_already_exist($this->category, $_SESSION["admin_name"]) == true) ? true : false;
    }

    private function is_description_input_valid() {
        return (!preg_match("/^[[a-zA-Z0-9 !?.]*$/", $this->description)) ? true : false;
    }

    private function is_amount_input_valid() {
        return (!preg_match("/^[[0-9]*$/", $this->amount)) ? true : false;
    }

    private function is_category_input_valid() {
        return (!preg_match("/^[[a-zA-Z0-9]*$/", $this->category)) ? true : false;
    }

    private function is_description_have_consecutive_spaces() {
        return (preg_match('/\s{2,}/', $this->description)) ? true : false;
    }

    private function is_description_all_spaces() {
        return (empty(trim($this->description))) ? true : false;   
    }

    private function is_add_data_inputs_empty() {
        return (empty($this->category) OR empty($this->description) OR empty($this->amount)) ? true : false;
    }







}