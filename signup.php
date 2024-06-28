<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GRING</title>
    <link rel="stylesheet" href="./css/styles.css?<?=filemtime('./css/styles.css');
clearstatcache();?>">
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
                <a href="index.php" style="text-decoration:none"><h2>Signup Here</h2></a>
                <form action="./php/includes/signup.inc.php" method="post">

<?php
//kung may error nd kwaon input kag e unset
if (isset($_SESSION['Errors_signup']["invalidAdminName"]) or isset($_SESSION['Errors_signup']["adminNameTaken"])) {

    echo '<input type="text" name="admin_name" placeholder="Admin Name" autofocus> <br>';
    unset($_SESSION['Signup_Data']["adminName"]);

} elseif (!isset($_SESSION['Errors_signup']["invalidAdminName"]) and !isset($_SESSION['Errors_signup']["adminNameTaken"]) and isset($_SESSION['Signup_Data']["adminName"])) {
    //if wla errors na kag may data then butang
    echo '<input type="text" name="admin_name" placeholder="Admin Name" autofocus value="' . $_SESSION['Signup_Data']["adminName"] . '"> <br> ';

} else {

    echo '<input type="text" name="admin_name" placeholder="Admin Name" autofocus> <br>';
}
?>

    <input type="password" name="pwd" placeholder="Password">
    <br>

    <select name="start-up_types">
        <option value="" disabled selected hidden>Start-up Type</option>
        <option value="Large_startup">Large Start-up</option>
        <option value="Normal_startup">Normal Start-up</option>
    </select>
    <br>

<?php //kung may error nd kwaon kag balik ang input.
if (isset($_SESSION['Errors_signup']["invalidEmail"]) or isset($_SESSION['Errors_signup']["emailTaken"])) {

    echo '<input type="email" name="email" placeholder="Email"> <br>';
    unset($_SESSION['Signup_Data']["email"]);

} elseif (!isset($_SESSION['Errors_signup']["invalidEmail"]) and !isset($_SESSION['Errors_signup']["emailTaken"]) and isset($_SESSION['Signup_Data']["email"])) { // kung wla pa sng input halin sa user sa sugod2 wla sang value na e butang balik

    echo '<input type="email" name="email" placeholder="Email" value="' . $_SESSION['Signup_Data']["email"] . '"> <br>';

} else { // tapos check sng tanan kag wla sng problema kag may input na from user then pwede na e balik kag butang

    echo '<input type="email" name="email" placeholder="Email"> <br>';
}?>

    <button type="submit" class="btnn" name="submit">Sign Up</button>
</form>

<?php if (isset($_SESSION['Errors_signup'])) {
    $errors = $_SESSION['Errors_signup'];

    foreach ($errors as $error) {
        ?>
            <script>alert(<?php echo json_encode($error) ?>)</script>
        <?php
}

    unset($_SESSION['Errors_signup']);
}
?>
                </div>
            </div>
        </div>
        <footer>
        <p>&copy; 2023 Giring</p>
    </footer>
</body>
</html>