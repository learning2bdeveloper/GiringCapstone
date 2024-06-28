<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
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
            <?php require '../../menubar.php';?>
            </div>
            </div>
    </header1>
        <div class="content">
            <h1>Inventory</h1> <br>

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

    <?php if (isset($_SESSION['Inventory_Add_Error'])) {
    $errors = $_SESSION['Inventory_Add_Error'];

    foreach ($errors as $error) {
        ?><script>alert(<?=json_encode($error)?>)</script><?php
}

    unset($_SESSION['Inventory_Add_Error']);
}?>

<?php if (isset($_SESSION['Inventory_Update_Error'])) {
    $errors = $_SESSION['Inventory_Update_Error'];

    foreach ($errors as $error) {
        ?><script>alert(<?=json_encode($error)?>)</script><?php
}

    unset($_SESSION['Inventory_Update_Error']);
}?>

    <script>



     let el_update_btn = document.querySelectorAll('.btnUpdate');
     let el_update_btn2 = document.getElementsByClassName('btnUpdate');

    //     el_update_btn.forEach((item) => {
    //         item.addEventListener('click', () => {
    //             console.log("hello");
    //         });
    //     });

//     document.addEventListener('click', function(event) {
//     if (event.target.matches('.btnUpdate')) {
//         e
//     }
// });

document.addEventListener('click', function(event) {
    if (event.target.matches('.btnUpdate')) {
        var btnUpdateId = event.target.getAttribute('pass-value');
        // Or you can use dataset property: var btnUpdateId = event.target.dataset.btnUpdate;
        console.log("Value of btnUpdate: " + btnUpdateId);
    }
});


        
       function showSearchTable(sel) {
            let data = {
                "currentAdmin": "<?=$_SESSION['admin_name'];?>",
                "value": sel.value
            }
            fetch('inventorySearch.php', {
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
        fetch('inventoryAddForm.php')
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
       ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        function showTableForm(page) {

            let currentAdmin = "<?=$_SESSION['admin_name'];?>"; // ga error kung nd sulod sng double quotes
            let session_data = {
                "currentAdmin": currentAdmin,
                "page": page
            }
            fetch('inventoryTableUpdater.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json; charset=utf-8'
                },
                body: JSON.stringify(session_data)
            }).then((response) => {
                return response.text();
            }).then((data) => {
                document.getElementById("table").innerHTML = data;
            }).catch((error) => {
                console.warn(error);
            })

        }
        /////////////////////////////////////////////////////////////////////////////
        async function deleteInventoryData(id, currentAmount) {
            try {
                let confirmation = confirm("Are you sure you want to delete this?");
                if(confirmation) {
                    let data = new FormData();
                    data.append("Delete", id);
                    data.append("currentAmount", currentAmount);
                    const response = await fetch('../../includes/inventory.inc.php', {
                        method: 'POST',
                        body: data
                    }).then(response=>{
                        return response.text();
                    }).then(response=>{
                        console.log(response);
                        location.href=response;
                    })
                }

            } catch (error) {
                console.warn(error);
            }
        }
        ////////////////////////////////////////////////////////////////////////////////////
        async function showUpdateForm(id) {
            try {
                data = {
                    "id": id
                }
                const response = await fetch('inventoryUpdateForm.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json; charset=utf-8'
                    },
                    body: JSON.stringify(data)
                })
                const info = await response.text();
                table.innerHTML = info;
            }catch(error) {
                console.warn(error);
            }
        }
        /////////////////////////////////////////////////////////////////////////////////////
        function updateInventory(id, current_item_no, current_item_name) {

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

            fetch('../../includes/inventory.inc.php', {
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