<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Net Income</title>
    <link rel="stylesheet" href="../../../css/styles.css?<?=filemtime('../../../css/styles.css');
clearstatcache()?>">
<script type="module" src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 <link rel="icon" type="image/png" href="../../../pictures/giringlogo.png">
</head>

<body onload="showChartForm();sideBar();">
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
            <h1>Projected Net Income</h1> <br>

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


        function showChartForm() {

            let datas = {
                "boss": "<?=$_SESSION['admin_name'];?>"
            }


            fetch('getNetIncomeChart.php', {
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
                        type: 'line',
                        data: {
                            labels: response['months'],
                            datasets: [{
                                label: 'Total Sales',
                                data: response['total_sales'],
                                backgroundColor: 'rgba(54, 162, 235, 0.5)', // Adjust colors as needed
                                borderColor: 'rgba(54, 162, 235, 1)', // Adjust colors as needed
                                borderWidth: 1
                            },
                            {
                                label: 'Total Expenses',
                                data: response['total_amount'],
                                backgroundColor: 'rgba(255, 99, 132, 0.5)', // Adjust colors as needed
                                borderColor: 'rgba(255, 99, 132, 1)', // Adjust colors as needed
                                borderWidth: 1
                            },
                            {
                                label: 'Net Income',
                                data: response['net_income'],
                                backgroundColor: 'rgba(255, 0, 0)', // Adjust colors as needed
                                borderColor: 'rgba(255, 0, 0)', // Adjust colors as needed
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


    //     function showSearchTable(sel) {
    //         let data = {
    //             "currentAdmin": "<?=$_SESSION['admin_name'];?>",
    //             "value": sel.value
    //         }
    //         fetch('salesSearch.php', {
    //             method: 'POST',
    //             headers: {
    //                 'Content-Type': 'application/json; charset=utf-8'
    //             },
    //             body: JSON.stringify(data)
    //         }).then(response=>{
    //             return response.text();
    //         }).then(response=>{
    //             table.innerHTML = response;
    //             if(document.getElementById("search").value == "") {
    //             showTableForm();
    //         }
    //         }).catch(error=>{
    //             console.warn(error.message);
    //         })
    //     }
    //     ///////////////////////////////////////////////////////////////////////////////////
    //     function addRow() {
    //         const table = document.getElementById('innerTable');
    //         const originalRow = table.rows[1]; // Get the second row (index 1)

    //         // Clone the original row
    //         const newRow = originalRow.cloneNode(true);

    //         // Clear input values in the new row
    //         const inputs = newRow.getElementsByTagName('input', 'textarea');
    //         for (let i = 0; i < inputs.length; i++) {
    //             inputs[i].value = '';
    //         }

    //         // Append the new row to the table
    //         table.appendChild(newRow);
    //     }
    //     //////////////////////////////////////////////////////////////////////////////////////////////////////////
    //     function showAddForm() { // finished
    //     fetch('salesAddForm.php')
    //         .then(response => {
    //             return response.text();
    //         })
    //         .then(response=>{
    //             table.innerHTML = response;
    //             monthlyChart.destroy();
    //             itemSales.destroy();
    //         })
    //         .catch(error=>{
    //             console.warn(error.message);
    //         })
    //    }
    //    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //     function showTableForm(page) {

    //         let currentAdmin = "<?=$_SESSION['admin_name'];?>"; // ga error kung nd sulod sng double quotes
    //         let session_data = {
    //             "currentAdmin": currentAdmin,
    //             "page": page
    //         }
    //         fetch('salesTableUpdater.php', {
    //             method: 'POST',
    //             headers: {
    //                 'Content-Type': 'application/json; charset=utf-8'
    //             },
    //             body: JSON.stringify(session_data)
    //         }).then((response) => {
    //             return response.text();
    //         }).then((data) => {
    //             document.getElementById("table").innerHTML = data;
    //             monthlyChart.destroy();
    //             itemSales.destroy();
    //             dailyChart.destroy();
    //         }).catch((error) => {
    //             console.warn(error.message);
    //         })

    //     }
    //     /////////////////////////////////////////////////////////////////////////////
    //     async function deleteSalesData(id) {
    //         try {
    //             let data = new FormData();
    //             data.append("Delete", id);
    //             const response = await fetch('../../includes/sales.inc.php', {
    //                 method: 'POST',
    //                 body: data
    //             }).then(response=>{
    //                 return response.text();
    //             }).then(response=>{
    //                 console.log(response);
    //                 location.href=response;
    //             })
    //         } catch (error) {
    //             console.warn(error.message);
    //         }
    //     }
    //     ////////////////////////////////////////////////////////////////////////////////////
    //     async function showUpdateForm(id) {
    //         try {
    //             data = {
    //                 "id": id
    //             }
    //             const response = await fetch('salesUpdateForm.php', {
    //                 method: 'POST',
    //                 headers: {
    //                     'Content-Type': 'application/json; charset=utf-8'
    //                 },
    //                 body: JSON.stringify(data)
    //             })
    //             const info = await response.text();
    //             table.innerHTML = info;
    //         }catch(error) {
    //             console.warn(error.message);
    //         }
    //     }
    //     /////////////////////////////////////////////////////////////////////////////////////
    //     function updatesales(id, current_item_no, current_item_name) {

    //         let data = new FormData();
    //         let itemNo = document.getElementById("itemNo").value;
    //         let itemName = document.getElementById("itemName").value;
    //         let itemDescription = document.getElementById("itemDescription").value;
    //         let quantity = document.getElementById("quantity").value;
    //         let amount = document.getElementById("amount").value;

    //         data.append("Update", id);
    //         data.append("currentItemNo", current_item_no);
    //         data.append("curretItemName", current_item_name);
    //         data.append("itemNo", itemNo);
    //         data.append("itemName", itemName);
    //         data.append("itemDescription", itemDescription);
    //         data.append("quantity", quantity);
    //         data.append("amount", amount);

    //         fetch('../../includes/sales.inc.php', {
    //             method: 'POST',
    //             body: data
    //         }).then(response=>{
    //             return response.text();
    //         }).then(response=>{
    //             location.href=response;
    //         }).catch(error=>{
    //             console.warn(error.message);
    //         })

    //     }

    //     function sideBar() {
    //             var sidebarItems = document.querySelectorAll('.menubar li');
    //             sidebarItems.forEach(function(item) {
    //                 if (item.querySelector('a').href === window.location.href) {
    //                 item.classList.add('active');
    //                 }
    //             });
    //             };

    //     function calculateTotal() { // done
    //         let amount = document.getElementById("amount").value;
    //         let quantity = document.getElementById("quantity").value;

    //         if((!amount == null || !amount == "") & (!quantity == null || !quantity == "")) {
    //             document.getElementById("total").value = amount * quantity;
    //         }
    //     }
    </script>
</body>

</html>