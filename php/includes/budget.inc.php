<?php
session_start();

require '../classes/database.classes.php';
require '../classes/budget.classes.php';
require '../controller/budget.controller.php';

if(isset($_POST['ADD'])) {

    $category = ucfirst($_POST['category']);
    $description = ucfirst($_POST['description']);
    $amount = ucfirst($_POST['amount']);

    //put the data to the controller for error_handling

    $budgetController = new BudgetController;
    $budgetController->set_add_budget_data($category, $description, $amount);
    $budgetController->insert_add_budget_data();
}

if(isset($_POST['delete'])) {
    $budget = new Budget;
    $budget->delete_budget_data($_POST['delete']);
}

if(isset($_POST['Update'])) {
    
    $id = ucfirst($_POST['Update']);
    $category = ucfirst($_POST['category']);
    $description = ucfirst($_POST['description']);
    $amount = ucfirst($_POST['amount']);
    $currentCategory = ucfirst($_POST['currentCategory']);

    $budgetController = new BudgetController;
    $budgetController->set_update_budget_data($id, $category, $description, $amount, $currentCategory); 
    $budgetController->insert_update_budget_data();
}