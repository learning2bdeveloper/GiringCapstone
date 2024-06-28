<?php
session_start();

require '../classes/database.classes.php';
require '../classes/expense.classes.php';
require '../controller/expense.controller.php';

if (isset($_POST['ADD'])) {

    $category = $_POST['category'];
    $budgetAmount = $_POST['budgetAmount'];
    $description = $_POST['description'];
    $expenseAmount = $_POST['expenseAmount'];
    $due = $_POST['due_date'];
    $payee = $_POST['payee'];

    //put the data to the controller for error_handling

    $ExpenseController = new ExpenseController;
    $ExpenseController->set_add_expense_data($category, $budgetAmount, $description, $expenseAmount, $due, $payee);
    $ExpenseController->insert_add_expense_data();

    echo 'expense.php';
}

if (isset($_POST['delete'])) {

    $id = $_POST['delete'];
    $categoryAmount = $_POST['categoryAmount'];
    $category = $_POST['category'];

    $ExpenseController = new ExpenseController;
    $ExpenseController->set_give_back_amount_after_deletion_of_expense_data_amount($id, $category, $categoryAmount);
    $ExpenseController->give_back_amount_after_deletion_of_expense_data_amount();

}

if (isset($_POST['Update'])) {

    $id = $_POST['Update'];
    $description = $_POST['description'];
    $categoryAmount = $_POST['categoryAmount'];

    $ExpenseController = new ExpenseController;
    $ExpenseController->set_update_expense_data($id, $description, $categoryAmount);
    $ExpenseController->insert_update_expense_data();
}
