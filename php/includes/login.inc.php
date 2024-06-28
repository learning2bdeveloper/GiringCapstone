<?php

if(isset($_POST['login'])) {
 
    //Grabbing the data
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];

    //Instantiate SignupContr class
    session_start();
    include_once '../classes/database.classes.php';
    include_once '../classes/login.classes.php';
    include_once '../controller/login.controller.php';
    
    $login = new LoginController($email, $pwd);

    //Running Error Handlers and users login 

    $login->loginUser();

    //Going back to front page
    header("location: ../../getstarted.php?error=noneproblem");
    exit();
}