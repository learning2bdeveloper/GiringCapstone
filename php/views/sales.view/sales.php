<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales</title>
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
                <?php require '../../menubar.php';?>
            </div>
            </div>
    </header1>
        <div class="content">
            <h1>Sales</h1> <br>

            <button onclick="showAddForm()"class="text-button"><a>ADD</a></button>
            <button onclick="showTableForm()"class="text-button"><a>TABLE</a></button>
            <button onclick="showChartForm()"class="text-button"><a>CHART</a></button>
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
                <canvas id="monthlyChart"></canvas>
                <canvas id="dailyChart"></canvas>
                <canvas id="itemSales"></canvas>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['sales_Add_Error'])) {
    $errors = $_SESSION['sales_Add_Error'];

    foreach ($errors as $error) {
        ?><script>alert(<?=json_encode($error)?>)</script><?php
}

    unset($_SESSION['sales_Add_Error']);
}?>

<?php if (isset($_SESSION['sales_Update_Error'])) {
    $errors = $_SESSION['sales_Update_Error'];

    foreach ($errors as $error) {
        ?><script>alert(<?=json_encode($error)?>)</script><?php
}

    unset($_SESSION['sales_Update_Error']);
}?>

    <script>
        let monthlyChart;
        let dailyChart
        let itemSales;

        function showChartForm() {

            let datas = {
                "boss": "<?=$_SESSION['admin_name'];?>"
            }


            fetch('salesChartForm.php', {
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
                            datasets: [{
                                label: 'Total Orders',
                                data: response['totalOrders'],
                                backgroundColor: 'rgba(54, 162, 235, 0.5)', // Adjust colors as needed
                                borderColor: 'rgba(54, 162, 235, 1)', // Adjust colors as needed
                                borderWidth: 1
                            },
                            {
                                label: 'Total Sales',
                                data: response['totalSales'],
                                backgroundColor: 'rgba(255, 99, 132, 0.5)', // Adjust colors as needed
                                borderColor: 'rgba(255, 99, 132, 1)', // Adjust colors as needed
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });

                    const ctx2 = document.getElementById('dailyChart').getContext('2d');
                    dailyChart = new Chart(ctx2, {
                        type: 'bar',
                        data: {
                            labels: response['days'],
                            datasets: [{
                                label: 'Total Orders',
                                data: response['daily_total_orders'],
                                backgroundColor: 'rgba(54, 162, 235, 0.5)', // Adjust colors as needed
                                borderColor: 'rgba(54, 162, 235, 1)', // Adjust colors as needed
                                borderWidth: 1
                            },
                            {
                                label: 'Total Sales',
                                data: response['daily_item_total_sales'],
                                backgroundColor: 'rgba(255, 99, 132, 0.5)', // Adjust colors as needed
                                borderColor: 'rgba(255, 99, 132, 1)', // Adjust colors as needed
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });

                    const ctx3 = document.getElementById('itemSales').getContext('2d');
                    itemSales = new Chart(ctx3, {
                        type: 'bar',
                        data: {
                            labels: response['item_name'],
                            datasets: [
                            {
                                label: 'Total Sales',
                                data: response['item_name_total_sales'],
                                backgroundColor: 'rgba(255, 99, 132, 0.5)', // Adjust colors as needed
                                borderColor: 'rgba(255, 99, 132, 1)', // Adjust colors as needed
                                borderWidth: 1
                            }]
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


        function showSearchTable(sel) {
            let data = {
                "currentAdmin": "<?=$_SESSION['admin_name'];?>",
                "value": sel.value
            }
            fetch('salesSearch.php', {
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
                console.warn(error.message);
            })
        }
        ///////////////////////////////////////////////////////////////////////////////////
        function addRow() {
            const table = document.getElementById('innerTable');
            const originalRow = table.rows[1]; // Get the second row (index 1)

            // Clone the original row
            const newRow = originalRow.cloneNode(true);

            // Clear input values in the new row
            const inputs = newRow.getElementsByTagName('input', 'textarea');
            for (let i = 0; i < inputs.length; i++) {
                inputs[i].value = '';
            }

            // Append the new row to the table
            table.appendChild(newRow);
        }
        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        function showAddForm() { // finished
        fetch('salesAddForm.php')
            .then(response => {
                return response.text();
            })
            .then(response=>{
                table.innerHTML = response;
                monthlyChart.destroy();
                itemSales.destroy();
            })
            .catch(error=>{
                console.warn(error.message);
            })
       }
       ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        function showTableForm(page) {

            let currentAdmin = "<?=$_SESSION['admin_name'];?>"; // ga error kung nd sulod sng double quotes
            let session_data = {
                "currentAdmin": currentAdmin,
                "page": page
            }
            fetch('salesTableUpdater.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json; charset=utf-8'
                },
                body: JSON.stringify(session_data)
            }).then((response) => {
                return response.text();
            }).then((data) => {
                document.getElementById("table").innerHTML = data;
                monthlyChart.destroy();
                itemSales.destroy();
                dailyChart.destroy();
            }).catch((error) => {
                console.warn(error.message);
            })

        }
        /////////////////////////////////////////////////////////////////////////////
        async function deleteSalesData(id) {
            try {
                let data = new FormData();
                data.append("Delete", id);
                const response = await fetch('../../includes/sales.inc.php', {
                    method: 'POST',
                    body: data
                }).then(response=>{
                    return response.text();
                }).then(response=>{
                    console.log(response);
                    location.href=response;
                })
            } catch (error) {
                console.warn(error.message);
            }
        }
        ////////////////////////////////////////////////////////////////////////////////////
        async function showUpdateForm(id) {
            try {
                data = {
                    "id": id
                }
                const response = await fetch('salesUpdateForm.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json; charset=utf-8'
                    },
                    body: JSON.stringify(data)
                })
                const info = await response.text();
                table.innerHTML = info;
            }catch(error) {
                console.warn(error.message);
            }
        }
        /////////////////////////////////////////////////////////////////////////////////////
        function updatesales(id, current_item_no, current_item_name) {

            let data = new FormData();
            let itemNo = document.getElementById("itemNo").value;
            let itemName = document.getElementById("itemName").value;
            let itemDescription = document.getElementById("itemDescription").value;
            let quantity = document.getElementById("quantity").value;
            let amount = document.getElementById("amount").value;

            data.append("Update", id);
            data.append("currentItemNo", current_item_no);
            data.append("curretItemName", current_item_name);
            data.append("itemNo", itemNo);
            data.append("itemName", itemName);
            data.append("itemDescription", itemDescription);
            data.append("quantity", quantity);
            data.append("amount", amount);

            fetch('../../includes/sales.inc.php', {
                method: 'POST',
                body: data
            }).then(response=>{
                return response.text();
            }).then(response=>{
                location.href=response;
            }).catch(error=>{
                console.warn(error.message);
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

        function calculateTotal() { // done
            let amount = document.getElementById("amount").value;
            let quantity = document.getElementById("quantity").value;

            if((!amount == null || !amount == "") & (!quantity == null || !quantity == "")) {
                document.getElementById("total").value = amount * quantity;
            }
        }
    </script>
</body>

</html>