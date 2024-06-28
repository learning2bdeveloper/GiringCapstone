<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Control</title>
    <link rel="stylesheet" href="../../../css/styles.css?<?= filemtime('../../../css/styles.css');
                                                            clearstatcache() ?>">
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
                        <?php require './../../navigations2.php'; ?>
                    </ul>
                </div>
            </div>
        </header>
        <div class="main1">
            <header1>
                <div class="navbar">
                    <div class="menu1">
                        <ul class="menubar">
                            <?php require '../../menubar.php'; ?>
                    </div>
                </div>
            </header1>
            <div class="content">
                <h1>Stock Control</h1>
                <div id="whole">
                    <button onclick="showAddForm()" class="text-button"><a>ADD</a></button>
                    <button onclick="showTableForm()" class="text-button"><a>TABLE</a></button>
                    <div class="search-bar">
                        <input type="text" id="search" placeholder="Search..." onkeyup="showSearchTable(this)">
                        <button>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0V0z" fill="none" />
                                <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5z" />
                            </svg>
                        </button>
                    </div>
                    <br>
                    <div id="InventoryInfos" style="display: none" ;>
                        <label for="originalQuantity">
                            <h6>Original Quantity
                                <input type="text" id="originalQuantity" class="custominput">
                            </h6>
                        </label>
                    </div>
                    <div id="table"></div>
                </div>
            </div>
        </div>

        <?php if (isset($_SESSION['stockcontrol_Create_Error'])) {
            $errors = $_SESSION['stockcontrol_Create_Error'];

            foreach ($errors as $error) {
        ?>
                <script>
                    alert(<?php echo json_encode($error) ?>)
                </script>
        <?php
            }

            unset($_SESSION['stockcontrol_Create_Error']);
        } ?>

        <?php if (isset($_SESSION['stockcontrol_Update_Error'])) {
            $errors = $_SESSION['stockcontrol_Update_Error'];

            foreach ($errors as $error) {
        ?>
                <script>
                    alert(<?php echo json_encode($error) ?>)
                </script>
        <?php
            }

            unset($_SESSION['stockcontrol_Update_Error']);
        } ?>



        <script>
            const table = document.getElementById("table");

            // These functions are for reveneuAddform.php
            // Automatically getting the item name, description, price based on what is the item No.

            function get_item_name_item_description_price(item_no) { //finished
                let data = {
                    "item_no": item_no.value
                }
                fetch('stockcontrolItemNameDescription.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json; charset=utf-8'
                    },
                    body: JSON.stringify(data)
                }).then(response => {
                    return response.json();
                }).then(response => {
                    console.log(response);
                    document.getElementById("itemName").value = response.item_name;
                    document.getElementById("itemDescription").value = response.item_description;
                    document.getElementById("price").value = response.price;
                    document.getElementById("originalQuantity").value = response.quantity;
                }).catch(error => {
                    console.warn(error);
                })
            }

            function calculateAmount() {
                let price = document.getElementById("price").value;
                let quantity = document.getElementById("quantity").value;
                if (price !== null & quantity !== null) {
                    document.getElementById("amount").value = price * quantity;
                }
            }

            function calculateTaxAndTotal() {
                let price = document.getElementById("price").value;
                let tax_rate = document.getElementById("tax_rate").value;
                let amount = parseInt(document.getElementById("amount").value);
                let total = document.getElementById("total");
                if (price !== null & tax_rate !== null) {
                    let tax = price * (tax_rate / 100);
                    document.getElementById("tax").value = tax;
                    let totalSum = amount + tax;
                    total.value = totalSum;
                }
            }

            // On the top, are the function for stockcontrolAddForm.php

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
                let boss = "<?= $_SESSION['admin_name'] ?>";
                let data = {
                    "value": sel.value,
                    "currentAdmin": boss
                }
                fetch('stockcontrolSearch.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json; charset=utf-8'
                    },
                    body: JSON.stringify(data)
                }).then(response => {
                    return response.text();
                }).then(response => {
                    table.innerHTML = response;
                    if (document.getElementById("search").value == "") {
                        showTableForm();
                    }
                }).catch(error => {
                    console.warn(error);
                })
            }

            async function showUpdateForm(sel) {
                data = {
                    "id": sel
                }
                const response = await fetch('UpdateForm.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json; charset=utf-8'
                    },
                    body: JSON.stringify(data)
                })
                const info = await response.text();
                //table.innerHTML = info;
            }

            function deletestockcontrolData(id, itemNo, quantity, boss) { // finished
                let confirmation = confirm('Are you sure you want to delete this?');
                if (confirmation) {
                    data = new FormData();
                    data.append("id", id);
                    data.append("itemNo", itemNo);
                    data.append("quantity", quantity);
                    data.append("deletestockcontrol", "");

                    fetch('../../includes/stockcontrol.inc.php', {
                        method: 'POST',
                        body: data
                    }).then(response => {
                        return response.text();
                    }).then(response => {

                        location.reload();
                    }).catch(error => {
                        console.warn(error);
                    })
                }

            }

            async function showTableForm(page) { // finished
                let currentUser = "<?= $_SESSION['admin_name']; ?>";
                let currentData = {
                    "currentAdmin": currentUser,
                    "page": page
                }

                try {
                    const response = await fetch('stockcontrolTableUpdater.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json; charset=utf-8'
                        },
                        body: JSON.stringify(currentData)
                    });
                    const data = await response.text();
                    table.innerHTML = data;
                    document.getElementById("search").value = "";
                    document.getElementById("InventoryInfos").style.display = "none";
                } catch (error) {
                    console.warn(error);
                }
            }

            function showAddForm() { // finished
                fetch('stockcontrolAddForm.php')
                    .then(response => {
                        return response.text();
                    })
                    .then(response => {
                        table.innerHTML = response;
                        document.getElementById("InventoryInfos").style.display = "block";
                    })
                    .catch(error => {
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