<?php

if(isset($_POST['submit'])) {
 
    //Grabbing the data
    $admin_name = $_POST['admin_name'];
    $pwd = $_POST['pwd'];
    $startup_types = $_POST['start-up_types'];
    $email = $_POST['email'];

    //Instantiate SignupContr class
    include_once '../classes/database.classes.php';
    include_once '../classes/signup.classes.php';
    include_once '../controller/signup.controller.php';
    
    $signup = new SignupController($admin_name, $pwd, $startup_types, $email);

    //Running Error Handlers and users signup 

    $signup->signupUser();

    //Going back to front page
    header("location: ../../index.php?error=none");
    exit();
}