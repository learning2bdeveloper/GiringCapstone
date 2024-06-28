<?php

require '../php/classes/database.classes.php';
require '../php/functions/salaryTaxCalculationsShowDatas.function.php';

$Database = new Database;

$query = "SELECT * FROM `salary` WHERE `empID_FK` = :empID_FK AND `admin_name_FK` = :boss";
$stmt = $Database->connect()->prepare($query);
$stmt->bindParam(':boss', $_POST['boss']);
$stmt->bindParam(':empID_FK', $_POST['empID']);
$stmt->execute();

if ($stmt->rowCount() > 0) {

    $errors = array();
    $pay_frequency;
    $rate;
    $daily_work_hours;
    $workDaysPerWeek;
    $pay_date;

    while ($row = $stmt->fetch()) {
        $pay_frequency = $row['pay_frequency'];
        $rate = $row['rate'];
        $daily_work_hours = $row['daily_work_hours'];
        $workDaysPerWeek = $row['work_days_per_week'];
        $pay_date = $row['pay_date'];
    }

    $data = showCalculatedTaxDatas($pay_frequency, $rate, $daily_work_hours, $workDaysPerWeek);

    $errors[] = array(
        "income_tax" => $data['income_tax'],
        "total_tax" => $data['total_tax'],
        "total_salary" => $data['total_salary'],
        "SSS" => $data['SSS'],
        "EmployeeSSS" => $data['EmployeeSSS'],
        "pagIbig" => $data['pagIbig'],
        "EmployeePagIbig" => $data['EmployeePagIbig'],
        "Philhealth" => $data['Philhealth'],
        "EmployeePhilhealth" => $data['EmployeePhilhealth'],
        "Pay_date" => $pay_date,
    );

    echo json_encode($errors);
} else {

    $errors[] = array(
        "income_tax" => "Not Yet Created!",
        "total_tax" => "Not Yet Created!",
        "total_salary" => "Not Yet Created!",
        "SSS" => "Not Yet Created!",
        "EmployeeSSS" => "Not Yet Created!",
        "pagIbig" => "Not Yet Created!",
        "EmployeePagIbig" => "Not Yet Created!",
        "Philhealth" => "Not Yet Created!",
        "EmployeePhilhealth" => "Not Yet Created!",
        "Pay_date" => "Not Yet Created!",
    );

}
$stmt = null;
