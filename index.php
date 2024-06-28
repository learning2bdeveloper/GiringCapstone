 <?php session_start();
if (isset($_GET['logout'])) { // dula ang sessions kung mag logout gamit href. nd lng nkon gamiton ang logout.inc sa includes folder kay href ang ara sa design para tinlo na
    session_unset();
    session_destroy();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GIRING</title>
    <link rel="stylesheet" href="./css/styles.css?<?=filemtime('./css/styles.css');
clearstatcache(); //para nd mag cache sa browser gina butang timestamp at the same time kada tapos gina clearstatcache kay ga return ang filemtime cache?>">
</head>

<body class="main">

    <div class="main">
        <div class="navbar">
            <div class="icon">

            </div>

            <div class="menu">

            </div>



        </div>
        <div class="content">
            <h1></h1>
            <br></br>
            <p class="par">
            </p>


            <div class="form">
                <h2>Login Here</h2>
                <form action="./php/includes/login.inc.php" method="post">
                    <input type="email" name="email" placeholder="Email">
                    <br>
                    <input type="password" name="pwd" placeholder="Password">
                    <br>
                    <br>
                    <button type="submit" class="btnn" name="login">Login</button>
                </form>

                <?php
if (isset($_SESSION['Errors_login'])) {
    $errors = $_SESSION['Errors_login'];

    foreach ($errors as $error) {
        ?>
                                <script>alert(<?php echo json_encode($error) ?>)</script>
                            <?php
}
    unset($_SESSION['Errors_login']);
}
?>

                <p class="link">Don't have an account<br>
                    <a href="signup.php">Sign up </a> here</a>
                </p>


            </div>
        </div>
    </div>
    </div>
    </div>
    <footer>
        <p>&copy; 2023 Giring</p>
    </footer>
</body>

</html>