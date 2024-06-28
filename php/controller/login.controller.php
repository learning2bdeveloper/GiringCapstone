<?php

class LoginController extends Login{
    
    private $email;
    private $pwd;

    public function __construct($email, $pwd) {

        $this->email = $email;
        $this->pwd = $pwd;

    }

    public function loginUser() {
        $error = [];

       if($this->isInputEmpty() == true) {
            $error["emptyInput"] = "Fill in the blanks!";
        }

        if($error) { // true ni siya if may unod

            $_SESSION['Errors_login'] = $error;
            header('Location: ../../index.php?login=error');
            die();
        }

        $this->getUser($this->email, $this->pwd);
    }
    // checking if one user input is not filled.
private function isInputEmpty() {
        if(empty($this->email) || empty($this->pwd)) {
            return true;
        }
        else {
            return false;
        }

    }

} 

?>