<!DOCTYPE html>
<html lang="en">
<head>
    <title>Net Income</title>
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
                    <a href="home.html" class="image-button">
                <img src="pictures/logo1.png" style="width:150px" "length:150px">
                </div>
                <ul>
                <?php require 'navigations.php';?>
                </ul>
            </div>
        </div>
        </header>
        <div class="main1">
    <header1>
            <div class="navbar">
            <div class="menu1">
            <ul class="menubar">
                    <li><a href="../../../php/views/revenue.view/revenue.php">Sales</a></li>
                    <li><a href="../../../php/views/expense.view/expense.php">Expenses</a></li>
                    <li><a href="netincome.php">Net Income</a></li>
                    <li><a href="../../../php/views/budgets.view/budget.php">Budgeting</a></li>
                    <li><a href="../../../php/views/employees.view/employee.php">Employee</a></li>
                    <li><a href="../../../php/views/salaries.view/salary.php">Payroll</a></li>
                    <li><a href="../../../php/views/revenue.view/revenue.php">Stock Control</a></li>
                    <li><a href="../../../php/views/inventory.view/inventory.php">Inventory</a></li>
                </ul>

            </div>
            </div>
    </header1>
        <div class="content">
            <h1>Net Income</h1>
            <table>
  <tr>
    <th>Date (Monthly)</th>
    <th>Total Sales</th>
    <th>Total Expenses</th>
    <th>Net Income</th>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
</table>

</div>
</div>
    <footer class="footer">
        <p>Empower Your Finances with Giring. Your Gateway to Financial Excellence.</p>
        <p>© 2023 Giring. All Rights Reserved.</p>
    </footer>
</body>
</html>