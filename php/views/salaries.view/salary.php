<?php
session_start(); // https://apacmonetary.com/fintech/how-to-compute-income-tax-in-the-philippines/
// https://www.aanyahr.com/post/new-sss-contributions-effective-2023#:~:text=Starting%20January%201%2C%202023%20as,from%20the%20current%20of%2013%20%25
// https://sprout.zendesk.com/hc/en-us/articles/360030537174-How-to-Calculate-Your-SSS-Contribution
// https://sprout.ph/blog/how-to-calculate-your-hdmf-contribution/#:~:text=Calculating%20your%20monthly%20Pag%2DIBIG,you%20earn%20over%20%E2%82%B11%2C500.
// https://sprout.ph/blog/philhealth-new-contribution-rates/
// new 2023 income tax https://www.dof.gov.ph/train-law-to-further-reduce-personal-income-taxes-in-2023-onwards/
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll</title>
    <link rel="stylesheet" href="../../../css/styles.css?<?=filemtime('../../../css/styles.css');
clearstatcache()?>">
 <link rel="icon" type="image/png" href="../../../pictures/giringlogo.png">
</head>

<body onload="showTableForm();sideBar();">
<div class="main1">
    <header>
        <div class="navbar">
            <div class="menu">
                <div class="icon">
                    <a href="home.php" class="image-button">
                <img src="../../../pictures/logo1.png" style="width:150px" "length:150px">
                </div>
                <ul>
                    <?php require './../../navigations2.php';?>
                </ul>
            </div>
        </div>
    </header>
    <div class="main1">
    <header1>
            <div class="navbar">
            <div class="menu1">
                <ul class="menubar">
                <?php require '../../menubar.php';?>
            </div>
            </div>
    </header1>
        <div class="content">
            <h1>Payroll</h1>
            <div id="whole">
                <button onclick="showHistoryTable()"class="text-button"><a>HISTORY</a></button>
                <button onclick="showAddForm()"class="text-button"><a>ADD</a></button>
                <button onclick="showTableForm()"class="text-button"><a>TABLE</a></button>
                <div class="search-bar">
                <input type="text" id="search" placeholder="Search..." onkeyup="showSearchTable(this)">
                <button>
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                      <path d="M0 0h24v24H0V0z" fill="none"/>
                      <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5z"/>
                  </svg>
              </button>
            </div>
                <div id="table"></div>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['employee_Set_Employee_Salary_Error'])) // finished
{
    $errors = $_SESSION['employee_Set_Employee_Salary_Error'];

    foreach ($errors as $error) {
        ?>
                        <script>alert(<?php echo json_encode($error) ?>)</script>
                        <?php
}

    unset($_SESSION['employee_Set_Employee_Salary_Error']);
}?>

    <?php if (isset($_SESSION['employee_Update_Employee_Salary_Error'])) // not finished
{
    $errors = $_SESSION['employee_Update_Employee_Salary_Error'];

    foreach ($errors as $error) {
        ?>
                        <script>alert(<?php echo json_encode($error) ?>)</script>
                        <?php
}

    unset($_SESSION['employee_Update_Employee_Salary_Error']);
}?>



    <script>
        const table = document.getElementById("table");

        function deleteHistorySalaryData(ID, currentAdmin) {
            let data = new FormData();
            data.append("ID", ID);
            data.append("currentAdmin", currentAdmin);
            data.append("deleteHistorySalaryData", "");

            fetch('../../includes/salary.inc.php', {
                method: 'POST',
                body: data
            }).then(response=>{
                return response.text();
            }).then(response=>{
                location.href=response;
            }).catch(error=>{
                console.warn(error);
            })
        }

        function showHistoryTable() {
            let currentUser = "<?=$_SESSION['admin_name'];?>";
            let currentData = {
                "currentAdmin": currentUser
            }

            fetch('salaryPaymentHistory.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json; charset=utf-8'
                },
                body: JSON.stringify(currentData)
            }).then(response=>{
                return response.text();
            }).then(response=>{
                table.innerHTML = response;
                document.getElementById("search").value = "";
            }).catch(error=>{
                console.warn(error);
            })
        }

        function paySalaryData(empID, pay_frequency, salary) { // done

            data = new FormData();
            data.append("empId", empID);
            data.append("pay_frequency", pay_frequency);
            data.append("salary", salary);
            data.append("paid", "");

            fetch('../../includes/salary.inc.php', {
                method: 'POST',
                body: data
            }).then(response=>{
                return response.text();
            }).then(response=>{
                console.log(response);
                location.href = response;
            }).catch(error=>{
                console.warn(error);
            })
        }


        function taxCalculations(pay_frequency, rate, daily_work_hours, work_days_per_week, empID) { // done
            data = new FormData();
            data.append("pay_frequency", pay_frequency);
            data.append("rate", rate);
            data.append("daily_work_hours", daily_work_hours);
            data.append("work_days_per_week", work_days_per_week);
            data.append("empID", empID);
            data.append("currentAdmin", "<?=$_SESSION['admin_name'];?>");

            fetch("salaryTaxCalculations.php", {
                method: "POST",
                body: data
            }).then(response=>{
                return response.text();
            }).then(response=>{
                console.log(response)
                table.innerHTML = response;
            }).catch(error=>{
                console.warn(error);
            })

        }

        function showSearchTable(sel) { // done
            let currentAdmin = "<?=$_SESSION['admin_name'];?>";
            let data = {
                "value": sel.value,
                "currentAdmin": currentAdmin
            }
            fetch('salarySearch.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json; charset=utf-8'
                },
                body: JSON.stringify(data)
            }).then(response=>{
                return response.text();
            }).then(response=>{
                table.innerHTML = response;
                if(document.getElementById("search").value == "") {
                showTableForm();
            }
            }).catch(error=>{
                console.warn(error);
            })
        }

        async function showUpdateForm(empID) { // done
            data = {
                "empID": empID
            }
            const response = await fetch('salaryUpdateForm.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json; charset=utf-8'
                },
                body: JSON.stringify(data)
            })
            const info = await response.text();
            table.innerHTML = info;
        }

        function deleteSalaryData(empID) { // done
            data = new FormData();
            data.append("deleteSalary", empID)

            fetch('../../includes/salary.inc.php', {
                method: 'POST',
                body: data
            }).then(response=>{
                showTableForm();
            }).catch(error=>{
                console.warn(error);
            })
        }

        async function showTableForm(page) { // done
            let currentUser = "<?=$_SESSION['admin_name'];?>";
            let currentData = {
                "currentAdmin": currentUser,
                "page": page
            }

            try {
            const response = await fetch('salaryTableUpdater.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json; charset=utf-8'
                },
                body: JSON.stringify(currentData)
            });
            const data = await response.text();
            table.innerHTML = data;
            document.getElementById("search").value = "";
            }catch(error) {
                console.warn(error);
            }
        }

        function showAddForm() { // finished
        fetch('salaryAddForm.php')
            .then(response => {
                return response.text();
            })
            .then(response=>{
                table.innerHTML = response;
            })
            .catch(error=>{
                console.warn(error);
            })
       }

        function sideBar() {
                var sidebarItems = document.querySelectorAll('.menubar li');
                sidebarItems.forEach(function(item) {
                    if (item.querySelector('a').href === window.location.href) {
                    item.classList.add('active');
                    }
                });
                };
    </script>
</body>
</html>