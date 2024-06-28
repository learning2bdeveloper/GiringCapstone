<?php
session_start();
require '../../classes/database.classes.php';
require '../../classes/budget.classes.php';
$postedData = json_decode(file_get_contents('php://input'),true);

$data = $postedData['value'];

$categoryAmount = new Budget;
$output = $categoryAmount->getAmount($_SESSION['admin_name'],$data);

echo $output->fetch()['Amount'];

            
            
    