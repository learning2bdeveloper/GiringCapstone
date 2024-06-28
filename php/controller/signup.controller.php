<?php

class SignupController extends Signup{
    
    private $admin_name;
    private $pwd;
    private $startup_types;
    private $email;

    public function __construct($admin_name, $pwd, $startup_types, $email) {

        $this->admin_name = $admin_name;
        $this->pwd = $pwd;
        $this->startup_types = $startup_types;
        $this->email = $email;

    }

    public function signupUser() {
        $errors = [];
    
        if($this->emptyInput() == false) {
            $errors["emptyInput"] = "Fill in the blanks!";
        }
        if($this->invalidAdminName() == false) {
            $errors["invalidAdminName"] = "Invalid Admin Name!";
        }
        if($this->invalidEmail() == false) {
            $errors["invalidEmail"] = "Invalid Email!";
        }
        if($this->adminNameCheck() == false) {
            $errors["adminNameTaken"] = "Admin Name Taken!";
        }
        if($this->emailCheck() == false) {
            $errors["emailTaken"] = "Email Taken!";
        }
        

        if($errors == true) { // true ni siya if may unod
            session_start();

            $_SESSION['Errors_signup'] = $errors;

            $userSignupData = [
                "adminName" => $this->admin_name,
                "startupType" => $this->startup_types,
                "email" => $this->email
            ];

            $_SESSION['Signup_Data'] = $userSignupData;

            header('Location: ../../signup.php?signup=error');
            die();
            exit();
        }
        
        $this->setUser($this->admin_name, $this->pwd, $this->startup_types, $this->email);
        
    }
    // checking if one user input is not filled.
private function emptyInput() {
    $result = "";
    if(empty($this->admin_name) || empty($this->pwd) || empty($this->startup_types) || empty($this->email)) {
        $result = false;
    }
    else {
        $result = true;
    }
    return $result;
}

//pattern matching meaning only these letters and numbers are allowed
private function invalidAdminName() {
    $result = "";
    if(!preg_match("/^[a-zA-Z0-9]*$/", $this->admin_name)) {
        $result = false;
    }
    else {
        $result = true;
    }
    return $result;
}

//validating if user email is the correct email
private function invalidEmail() {
    $result = "";
    if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
        $result = false;
    }
    else {
        $result = true;
    }
    return $result;
}

//checking if the username and email are already created in the database
private function adminNameCheck() {
    $result = "";
    if(!$this->checkAdminName($this->admin_name)) {
        $result = false;
    }else {
        $result = true;
    }
    return $result;
    }

private function emailCheck() {
    $result = "";
    if(!$this->checkEmail($this->email)) {
        $result = false;
    }else {
        $result = true;
    }
    return $result;
    }
}    