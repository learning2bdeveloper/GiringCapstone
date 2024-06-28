<?php session_start(); // wla pa ang learnmore!?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home</title>
    <link rel="stylesheet" href="css/styles.css?<?=filemtime('./css/styles.css');
clearstatcache();?>">
    <link rel="icon" type="image/png" href="pictures/giringlogo.png">
</head>
<body>
    <div class="main3">
    <header>
        <div class="navbar">
            <div class="menu">
                <div class="icon">
                    <a href="home.php" class="image-button">
                <img src="pictures/logo1.png" style="width:150px" "length:150px">
                </div>
                <ul>
                    <?php require 'navigations.php';?>
                    <li><h3 style="text-align:left">Hello <?=$_SESSION['admin_name']?>!</li>
                </ul>
            </div>
        </div>
        </header>

        <div class="main1">
            <div class="content1">
                <h3 style="text-align: center;" class="par">No matter what you want to do with your money, it starts with a budget.</h3>
                <h4 style="text-align: center;" class="par">Giring helps you prepare for it all.</h4>

                <section id="about">
                    <img src="pictures/home_banner.png" alt="banner">
                </section>

                <div class="form2">
                    <!-- <button class="btnn"><a href="learnmore.php">Learn More</a></button> -->
                </div>
            </div>
        </div>
            <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
    </div>
    <footer>
        <p>Empower Your Finances with Giring. Your Gateway to Financial Excellence.</p>
        <p>© 2023 Giring. All Rights Reserved.</p>
    </footer>
</body>
</html>