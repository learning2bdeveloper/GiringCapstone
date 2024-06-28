<?php

require '../php/classes/database.classes.php';

// $errors = array();
// $errors[] = array("response" => "Login Success!", "checkedEmpID" => "hello");
// echo json_encode($errors);
//if (isset($_POST['LOGIN'])) {
// $database = new Database;
// //$empID = $_POST['EMPID'];
// $empID = "e2311281";
// $query = "SELECT * FROM `employee` WHERE `empID` = :empID";  OUTPUT [{"ID":1,"Owner_admin_name_FK":"Kan","empID":"e2311281","first_name":"Oscar Kyanu","last_name":"Kho","city":"Bacolod City","street":"Hermilinda Homes","birth_date":"2001-10-09","phone":"09060880593","hired_date":"2023-11-28","password":"$2y$10$6Hw.fsH7w1RqDL4W6ib23OKhv.jhoVECfwPyVBE1QRj4umg663dvq","active":0}]
// $stmt = $database->connect()->prepare($query);
// $stmt->bindParam('empID', $empID);
// if (!$stmt->execute()) {
//     $stmt = null;
//     die();
// }

// while ($result = $stmt->fetchAll()) {
//     $data = $result;
// }

// echo json_encode($data);
//}

if (isset($_POST['LOGIN'])) {

    $errors = array();
    $empID = $_POST['EMPID'];
    $password = $_POST['PASSWORD'];

    if (empty($empID) or empty($password)) {
        $errors = array();
        $errors[] = array("response" => "Empty Inputs!");
        echo json_encode($errors);
    }

    $database = new Database;

//unahon lng danay ang kwa password
    $query = "SELECT * FROM `employee` WHERE `empID` = :username";

    $stmt = $database->connect()->prepare($query);

    $stmt->bindParam(':username', $empID);

    if (!$stmt->execute()) {
        # same output as fetchAll() which JSON ARRAY OBJECTS E.G. [{"password":"$2y$10$6Hw.fsH7w1RqDL4W6ib23OKhv.jhoVECfwPyVBE1QRj4umg663dvq"}]
        $errors[] = array("password" => "failed Query!");
        echo json_encode($errors);
        die();
    }

    if ($stmt->rowCount() === 0 or $stmt->rowCount() === false) {

        $errors[] = array("response" => "Employee Doesn't exist!"); // [{"response":"Employee Doesn't exist!"}]
        echo json_encode($errors);
        die();
    }

    $data;
    while ($result = $stmt->fetch()) {
        $data = $result;
    }

    $checkedpwd = $data['password'];
    if (!password_verify($password, $checkedpwd)) {

        $errors[] = array("response" => "WRONG PASSWORD!");
        echo json_encode($errors);
    }

    $queryActiveStatus = "UPDATE `employee` SET `status`= 'Online' WHERE `Owner_admin_name_FK` = :boss AND `empID` = :empID;";
    $stmt3 = $database->connect()->prepare($queryActiveStatus);
    $stmt3->bindParam(':empID', $data['empID']);
    $stmt3->bindParam(':boss', $data['Owner_admin_name_FK']);
    if (!$stmt3->execute()) {
        # same output as fetchAll() which JSON ARRAY OBJECTS E.G. [{"password":"$2y$10$6Hw.fsH7w1RqDL4W6ib23OKhv.jhoVECfwPyVBE1QRj4umg663dvq"}]
        $errors[] = array("response" => "failed Query!");
        echo json_encode($errors);
        die();
    }

    $errors[] = array(
        "response" => "Login Success!",
        "checkEmployeeID" => $data['empID'],
        "boss" => $data['Owner_admin_name_FK'],
        "firstName" => $data['first_name'],
        "lastName" => $data['last_name']);
    echo json_encode($errors);
    $errors = array(); // pang refresh
    //unset($errors);

    // $query2 = "SELECT `*` FROM `employee` WHERE `empID` = :username2";tapuson ko lng ni karon

    // $stmt2 = $database->connect()->prepare($query2);

    // $stmt2->bindParam(':username2', $empID);

    // $stmt2->execute();

    // if($stmt2->rowCount() == 0) {
    //     $errors = array();
    //     $errors[] = array("password" => "WRONG USERNAME!");
    //     echo json_encode($errors);
    // }

}
