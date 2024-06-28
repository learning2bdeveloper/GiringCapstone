<?php

class Signup extends Database
{

    protected function setUser($admin_name, $pwd, $startup_types, $email)
    {
        $stmt = $this->connect()->prepare("INSERT INTO `user`(`Admin_Name`, `Password`, `Start-up_Type`, `Email`) VALUES (:ad, :p, :st, :e);");

        $hashed_password = password_hash($pwd, PASSWORD_DEFAULT);

        $stmt->bindParam('ad', $admin_name);
        $stmt->bindParam('p', $hashed_password);
        $stmt->bindParam('st', $startup_types);
        $stmt->bindParam('e', $email);
        if (!$stmt->execute()) {
            $stmt = null;
            header("location: ../../signup.php?error=stmtfailed");
            exit();
        }
        $stmt = null;
    }

    protected function checkAdminName($admin_name)
    {
        $stmt = $this->connect()->prepare("SELECT `Admin_Name` FROM `user` WHERE `Admin_Name` = :an");
        $stmt->bindParam('an', $admin_name);

        if (!$stmt->execute()) {
            $stmt = null;
            header("location: ../../signup.php?error=stmtfailed");
            exit();
        }

        //Checking to see if there is a user already exist in the database, then don't signup the user
        $resultChecker = "";
        if ($stmt->rowCount() > 0) {
            $resultChecker = false;
        } else {
            $resultChecker = true;
        }
        return $resultChecker;
    }

    protected function checkEmail($email)
    {
        $stmt = $this->connect()->prepare("SELECT `Admin_Name` FROM `user` WHERE `Email` = :e;");
        $stmt->bindParam('e', $email);

        if (!$stmt->execute()) {
            $stmt = null;
            header("location: ../../signup.php?error=stmtfailed");
            exit();
        }

        //Checking to see if there is a user already exist in the database, then don't signup the user
        $resultChecker = "";
        if ($stmt->rowCount() > 0) {
            $resultChecker = false;
        } else {
            $resultChecker = true;
        }
        return $resultChecker;
    }
}
