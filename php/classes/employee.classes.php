<?php

class Employee extends Database
{
//ilislan man address kag name
    protected function setEmployee($currentDate, $boss, $empID, $empFirstName, $empLastName, $empCity, $empStreet, $empBirthdate, $empPhone, $empPwd)
    {
        $query = "INSERT INTO `employee`(`Owner_admin_name_FK`, `empID`, `first_name`, `last_name`, `city`, `street`, `birth_date`, `phone`, `hired_date`, `password`) VALUES (:boss, :employeeID, :employeeFirstName, :employeeLastName, :employeeCity, :employeeStreet, :employeeBirth_date, :employeePhone, :employeeHired_date, :employeePassword);";
        $stmt = $this->connect()->prepare($query);

        $hashed_password = password_hash($empPwd, PASSWORD_DEFAULT);

        $stmt->bindParam('boss', $boss);
        $stmt->bindParam('employeeID', $empID);
        $stmt->bindParam('employeeFirstName', $empFirstName);
        $stmt->bindParam('employeeLastName', $empLastName);
        $stmt->bindParam('employeeCity', $empCity);
        $stmt->bindParam('employeeStreet', $empStreet);
        $stmt->bindParam('employeeBirth_date', $empBirthdate);
        $stmt->bindParam('employeePhone', $empPhone);
        $stmt->bindParam('employeeHired_date', $currentDate);
        $stmt->bindParam('employeePassword', $hashed_password);

        if (!$stmt->execute()) {
            $stmt = null;
            header('Location: ../../employee.php?error=stmtfailed');
            exit();
        }
    }
    //illislan ang address kag name $this->currentID, $boss, $this->empID, $this->empFirstName, $this->empLastName, $this->empCity, $this->empStreet, $this->empBirthdate, $this->empPhone, $this->empPwd
    protected function editEmployee($oldEmpID, $boss, $empID, $empFirstName, $empLastName, $empCity, $empStreet, $empBirthdate, $empPhone, $empPwd)
    {
        $query = "UPDATE `employee` SET `empID`=:empID,`first_name`=:empFirstName,`last_name`=:empLastName,`city`=:empCity,`street`=:empStreet,`birth_date`=:birth_date,`phone`=:phone,`password`=:empPassword WHERE `Owner_admin_name_FK` = :boss AND `empID` = :oldEmpID";
        $stmt = $this->connect()->prepare($query);

        $hashed_password = password_hash($empPwd, PASSWORD_DEFAULT);

        $stmt->bindParam('boss', $boss);
        $stmt->bindParam('oldEmpID', $oldEmpID);
        $stmt->bindParam('empID', $empID);
        $stmt->bindParam('empFirstName', $empFirstName);
        $stmt->bindParam('empLastName', $empLastName);
        $stmt->bindParam('empCity', $empCity);
        $stmt->bindParam('empStreet', $empStreet);
        $stmt->bindParam('birth_date', $empBirthdate);
        $stmt->bindParam('phone', $empPhone);
        $stmt->bindParam('empPassword', $hashed_password);

        if (!$stmt->execute()) {
            $stmt = null;
            header('Location: ../../employee.php?error=editfailed');
            exit();
        }

    }

    protected function salaryEmpID($boss, $currentEmpID, $newEmpID)
    { // checked
        $query = "UPDATE `salary` SET `empID_FK` = :newEmpID WHERE `empID_FK` = :currentEmpID AND `admin_name_FK` = :boss";
        $stmt = $this->connect()->prepare($query);

        $stmt->bindParam(':currentEmpID', $currentEmpID);
        $stmt->bindParam(':boss', $boss);
        $stmt->bindParam(':newEmpID', $newEmpID);

        if (!$stmt->execute()) {
            $stmt = null;
            echo "employee.php?error!";
            exit();
        }
    }

    protected function inventoryEmpID($boss, $currentEmpID, $newEmpID)
    { // checked
        $query = "UPDATE `inventory` SET `empID_FK` = :newEmpID WHERE `empID_FK` = :currentEmpID AND `Owner_admin_name_FK` = :boss";
        $stmt = $this->connect()->prepare($query);

        $stmt->bindParam(':currentEmpID', $currentEmpID);
        $stmt->bindParam(':boss', $boss);
        $stmt->bindParam(':newEmpID', $newEmpID);

        if (!$stmt->execute()) {
            $stmt = null;
            echo "employee.php?error!";
            exit();
        }
    }

    protected function revenueEmpID($boss, $currentEmpID, $newEmpID)
    { // checked
        $query = "UPDATE `revenue` SET `empID_FK` = :newEmpID WHERE `empID_FK` = :currentEmpID AND `Owner_admin_name_FK` = :boss";
        $stmt = $this->connect()->prepare($query);

        $stmt->bindParam(':currentEmpID', $currentEmpID);
        $stmt->bindParam(':boss', $boss);
        $stmt->bindParam(':newEmpID', $newEmpID);

        if (!$stmt->execute()) {
            $stmt = null;
            echo "employee.php?error!";
            exit();
        }
    }

    protected function expenseEmpID($boss, $currentEmpID, $newEmpID)
    { // checked
        $query = "UPDATE `expense` SET `empID_FK` = :newEmpID WHERE `empID_FK` = :currentEmpID AND `Owner_admin_name_FK` = :boss";
        $stmt = $this->connect()->prepare($query);

        $stmt->bindParam(':currentEmpID', $currentEmpID);
        $stmt->bindParam(':boss', $boss);
        $stmt->bindParam(':newEmpID', $newEmpID);

        if (!$stmt->execute()) {
            $stmt = null;
            echo "employee.php?error!";
            exit();
        }
    }

    protected function deleteEmployee($empID)
    {

        $query = "";
        if ($this->is_exist_check_employee_salary_id($empID) == true) { // kung delete ang employee then ma delete pati ang employee salary
            $query = "DELETE `employee`, `salary` FROM `employee` JOIN `salary` ON employee.empID = salary.empID_FK WHERE employee.empID = :empID";
        } else {
            $query = "DELETE `employee` FROM `employee` WHERE `empID` = :empID";
        }

        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam('empID', $empID);

        if (!$stmt->execute()) {
            $stmt = null;
            header('Location: ../../employee.php?error=deletefailed');
            exit();
        }
    }

    protected function is_exist_check_employee_salary_id($empID)
    {
        $query = "SELECT `empID_FK`FROM `salary` WHERE `empID_FK` = :employeeID";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam('employeeID', $empID);

        if (!$stmt->execute()) {
            $stmt = null;
            header('Location: ../../employee.php?error=stmtfailed');
            exit();
        }

        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    ////////////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // protected function check_employee_id($empID, $boss) { nd ni amo ang proper na gusto ni ms G
    //     $query = "SELECT `empID` FROM `employee` WHERE `empID` = :employeeID AND `Owner_admin_name_FK` = :boss";
    //     $stmt = $this->connect()->prepare($query);
    //     $stmt->bindParam('employeeID', $empID);
    //     $stmt->bindParam('boss', $boss);

    //     if(!$stmt->execute()) {
    //         $stmt = null;
    //         header('Location: ../../employee.php?error=stmtfailed');
    //         exit();
    //     }

    //     if($stmt->rowCount() > 0) {
    //         return true;
    //     }else {
    //         return false;
    //     }
    // }

    public function check_employee_id($empID)
    {
        $query = "SELECT `empID` FROM `employee` WHERE `empID` = :employeeID";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam('employeeID', $empID);

        if (!$stmt->execute()) {
            $stmt = null;
            header('Location: ../../employee.php?error=stmtfailed');
            exit();
        }

        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    protected function check_employee_phone_number($empPhone)
    {
        $query = "SELECT `phone` FROM `employee` WHERE `phone` = :employeePhone";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam('employeePhone', $empPhone);

        if (!$stmt->execute()) {
            $stmt = null;
            header('Location: ../../employee.php?error=stmtfailed');
            exit();
        }

        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function search_employee_table($input, $boss)
    {
        try {
            $query = "SELECT * FROM `employee` WHERE `Owner_admin_name_FK` = :boss AND (`empID` LIKE :input  OR `first_name` LIKE :input OR `last_name` LIKE :input OR `city` LIKE :input OR `street` LIKE :input OR `phone` LIKE :input OR `birth_date` LIKE :input OR `hired_date` LIKE :input)";
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

    public function get_employees_data($empID)
    { // no need mag butang admin kay PK ang empID so bawal mag ka same empID between owners
        $query = "SELECT * FROM `employee` WHERE `empID` = :empID;";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam('empID', $empID);
        $stmt->execute();

        if (!$stmt->execute()) {
            $stmt = null;
            header('Location: ../views/employees.view/employeeUpdate.php?error=stmtfailed');
            exit();
        }

        return $stmt;
    }

}
