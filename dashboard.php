<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/styles.css?<?=filemtime('./css/styles.css');
clearstatcache();?>">
    <link rel="icon" type="image/png" href="pictures/giringlogo.png">
</head>
<body>
<div class="main1">
        <header>
        <div class="navbar">
            <div class="menu">
                <div class="icon">
                    <a href="home.php" class="image-button">
                <img src="pictures/logo1.png" style="width:150px" "length:150px">
                </div>
                <ul>
                    <?php require 'navigations.php';?>
                    <div class="profile-icon">
                        <a href="profile.html" class="image-button">
                        <img src="pictures/profile-picture.png" alt="Profile Picture">
                    </a>
                    </div>
                    <li><h1 style="text-align:left"><?=$_SESSION['admin_name']?>'s Dashboard</li>

                </ul>
            </div>
        </div>
        </header>
        <div class="content1">
                <br>
                <div class="dashboard-menu">
                    <a href="./php/views/sales.view/sales.php" div class="menu-tile">Sales</a>
                    <a href="./php/views/expense.view/expense.php" div class="menu-tile1">Expenses</a>
                    <a href="./php/views/netincome.view/netincome.php" div class="menu-tile2">Net Income</a>
                    <a href="./php/views/budgets.view/budget.php" div class="menu-tile3">Budgeting</a>
                </div>
                <div class="dashboard-menu1">
                    <a href="./php/views/employees.view/employee.php" div class="menu-tile4">Employee</a>
                    <a href="./php/views/salaries.view/salary.php" div class="menu-tile5">Payroll</a>
                    <a href="./php/views/inventory.view/inventory.php" div class="menu-tile6">Inventory</a>
                    <a href="./php/views/stockcontrol.view/stockcontrol.php" div class="menu-tile7">StockControl</a>
                </div>
        </div>
    </div>
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>

    <footer class="footer">
        <p>Empower Your Finances with Giring. Your Gateway to Financial Excellence.</p>
        <p>© 2023 Giring. All Rights Reserved.</p>
    </footer>
</body>
</html>