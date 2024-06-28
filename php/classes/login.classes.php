<?php

class Login extends Database {

    protected function getUser($email, $pwd) {
        $stmt = $this->connect()->prepare("SELECT `Password` FROM `user` WHERE  `Email` = :e");

        $stmt->bindParam('e', $email);

        if(!$stmt->execute()) {
            $stmt = null;
            header("location: ../../index.php?error=stmtfailed");
            exit();
        }

        //checking if the input of the user (email) is found in the database; if not then error.
        if($stmt->rowCount() == 0) {

            $stmt = null;
            $error = [];
            $error["usernotfound"] = "User is not found!";
            $_SESSION['Errors_login'] = $error;
            header("location: ../../index.php?error=usernotfound");
           
            exit();
        }

        // $pwdHashed = $stmt->fetch(); // fetchAll
        // $checkedpwd = $pwdHashed['Password']; // [0][userpwd]
        //$checkedpwd = $pwdHashed;//password_verify($pwd, $pwdHashed[0]['users_pwd']);
        
        $dbpwd = $stmt->fetch()['Password'];
        $checkedpwd = $dbpwd;

        if(password_verify($pwd, $checkedpwd)) {
            
        $stmt = $this->connect()->prepare("SELECT * FROM `user` WHERE `Email` = :u AND `Password` = :p;");

            $stmt->bindParam('u', $email);
            $stmt->bindParam('p', $checkedpwd);

            if(!$stmt->execute()) {
                $stmt = null;
                header("location: ../../index.php?error=stmtfailed");
                exit();
            }

            if($stmt->rowCount() == 0) {
                $stmt = null;
                header("location: ../../index.php?error=noUser");
                exit();
            }

             $data = $stmt->fetch();

            session_start();
            $_SESSION['admin_name'] = $data['Admin_Name'];
           

            if($_SESSION["admin_name"] == null) {
                header("location: ../../index.php?error=sessionNull");
                exit();
            }
            $stmt = null;

            //add login info
            $user = $data['Admin_Name'];
            $date = date('Y-m-d H:i:s');
            $stmt = $this->connect()->prepare('INSERT INTO `loginhistory` (`username`, `timestamp`) VALUES (:username, :clock)');
            $stmt->bindParam('username', $user);
            $stmt->bindParam('clock', $date);

            if(!$stmt->execute()) {
                $stmt = null;
                header("location: ../../index.php?error=stmtfailed");
                exit();
            }

        }else {

            $stmt = null;
            $error = [];
            $error["wronpassword"] = "Wrong password!";
            $_SESSION['Errors_login'] = $error;
            header("location: ../../index.php?error=wrongpassword");
            exit();

        }
       
    }
}
?>