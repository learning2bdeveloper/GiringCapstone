<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenses</title>
    <link rel="stylesheet" href="../../../css/styles.css?<?=filemtime('../../../css/styles.css');
clearstatcache()?>">
<script type="module" src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <h1>Expenses</h1> <br>
            <div id="whole">
                <button onclick="showAddForm()" class="text-button"><a>ADD</a></button>
                <button onclick="showTableForm()" class="text-button"><a>TABLE</a></button>
                <div class="search-bar">
                <input type="text" id="search" placeholder="Search..." onkeyup="showSearchTable(this)">
                <button>
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                      <path d="M0 0h24v24H0V0z" fill="none"/>
                      <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5z"/>
                  </svg>
              </button>
            </div>
            <button onclick="showChartForm()" class="text-button"><a>CHART</a></button>
                <div id="table"></div>
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['expense_Create_Error'])) {
    $errors = $_SESSION['expense_Create_Error'];

    foreach ($errors as $error) {
        ?>
                        <script>alert(<?php echo json_encode($error) ?>)</script>
                        <?php
}

    unset($_SESSION['expense_Create_Error']);
}?>

    <?php if (isset($_SESSION['expense_Update_Error'])) {
    $errors = $_SESSION['expense_Update_Error'];

    foreach ($errors as $error) {
        ?>
                        <script>alert(<?php echo json_encode($error) ?>)</script>
                        <?php
}

    unset($_SESSION['expense_Update_Error']);
}?>



    <script>
         let monthlyChart;


function showChartForm() {

    let datas = {
        "boss": "<?=$_SESSION['admin_name'];?>"
    }


    fetch('getExpenseChart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json; charset=utf-8'
        },
        body: JSON.stringify(datas)
    }).then(wait=>{
        return wait.json();
    }).then(response=>{
        console.log(response);

            const ctx = document.getElementById('monthlyChart').getContext('2d');
            monthlyChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: response['months'],
                    datasets: [
                    {
                        label: 'Total Expenses',
                        data: response['total_expense'],
                        backgroundColor: 'rgba(255, 99, 132, 0.5)', // Adjust colors as needed
                        borderColor: 'rgba(255, 99, 132, 1)', // Adjust colors as needed
                        borderWidth: 1
                    },
                ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });


    }).catch(error=>{
        console.warn(error.message);
    })
}
        const table = document.getElementById("table");

        function category_budget(sel) { //finished
            let data = {
                "value": sel.value
            }
            fetch('expenseCategoryBudget.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json; charset=utf-8'
                },
                body: JSON.stringify(data)
            }).then(response=>{
                return response.text();
            }).then(response=>{
                console.log(response);
                document.getElementById("budgetAmount").value = response;
            }).catch(error=>{
                console.warn(error);
            })
        }

        async function updateExpense(id, categoryAmount) { // halin sa expenseUpdateForm.php

            let description = document.getElementById("description").value;
            let data = new FormData();
            data.append("Update", id);
            data.append("description", description);
            data.append("categoryAmount", categoryAmount);

            const response = await fetch('../../includes/expense.inc.php', {
                method: 'POST',
                body: data
            })
            const info = await response.text();
            location.href = info;
        }

        function showSearchTable(sel) {
            let data = {
                "currentAdmin": "<?=$_SESSION['admin_name'];?>",
                "value": sel.value
            }
            fetch('expenseSearch.php', {
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

        async function showUpdateForm(sel) {
            data = {
                "id": sel
            }
            const response = await fetch('expenseUpdateForm.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json; charset=utf-8'
                },
                body: JSON.stringify(data)
            })
            const info = await response.text();
            table.innerHTML = info;
        }

        function deleteExpenseData(id, categoryAmount, category) { // finished
            let confirmation = confirm('Are you sure you want to delete this?');
            if(confirmation) {
            data = new FormData();
            data.append("delete", id);
            data.append("categoryAmount", categoryAmount);
            data.append("category", category);

            fetch('../../includes/expense.inc.php', {
                method: 'POST',
                body: data
            }).then(response=>{
                showTableForm();
            }).catch(error=>{
                console.warn(error);
            })
        }
    }

        async function showTableForm(page) { // finished
            let currentUser = "<?=$_SESSION['admin_name'];?>";
            let currentData = {
                "currentAdmin": currentUser,
                "page": page
            }

            try {
            const response = await fetch('expenseTableUpdater.php', {
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
        fetch('expenseAddForm.php')
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

       function AddData() { // finished
        let confirmation = confirm("Are you sure you want to add this?");
        if(confirmation) {
                event.preventDefault();
        let category = document.getElementById("category").value;
        let description = document.getElementById("description").value;
        let budgetAmount = document.getElementById("budgetAmount").value;
        let expenseAmount = document.getElementById("expenseAmount").value;
        let payee = document.getElementById("payee").value;
        let due = document.getElementById("due_date").value;
        let data = new FormData();
        data.append("ADD", "");
        data.append("category", category);
        data.append("budgetAmount", budgetAmount);
        data.append("description", description);
        data.append("expenseAmount", expenseAmount);
        data.append("payee", payee);
        data.append("due_date", due);
        fetch('../../includes/expense.inc.php', {
            method: 'POST',
            body: data
        })
        .then(response=>{
            return response.text();
        })
        .then(response=>{
            console.log(response);
            location.href = response;   //
                                        //  diri ga work kung may error lng kay sa inc file na gin echo ko pero kung
                                        //  wla sa showtableform() diriso kay sa function na ara sa body na onload();
        })
        .catch(error=>{
            console.warn(error);
        })
        }

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