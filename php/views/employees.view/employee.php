<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee</title>
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
            <h1>Employees</h1>
            <div id="whole">
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

    <?php if (isset($_SESSION['employee_Create_Error'])) //done
{
    $errors = $_SESSION['employee_Create_Error'];

    foreach ($errors as $error) {
        ?>
                        <script>alert(<?php echo json_encode($error) ?>)</script>
                        <?php
}

    unset($_SESSION['employee_Create_Error']);
}?>

    <?php if (isset($_SESSION['employee_Edit_Error'])) {
    $errors = $_SESSION['employee_Edit_Error'];

    foreach ($errors as $error) {
        ?>
                        <script>alert(<?php echo json_encode($error) ?>)</script>
                        <?php
}

    unset($_SESSION['employee_Edit_Error']);
}?>



    <script>

        const table = document.getElementById("table");

        async function updateEmployee(currentID, currentPhone) { // done
            let empID = document.getElementsByName("empID")[0].value;
            let empFirstName = document.getElementsByName("empFirstName")[0].value;
            let empLastName = document.getElementsByName("empLastName")[0].value;
            let empCity = document.getElementsByName("empCity")[0].value;
            let empStreet = document.getElementsByName("empStreet")[0].value;
            let empBirthdate = document.getElementsByName("empBirthdate")[0].value; // need specify ang index kay getElementsByName
            let empPhone = document.getElementsByName("empPhone")[0].value;
            let empPwd = document.getElementsByName("empPwd")[0].value;

            let data = new FormData();
            data.append("currentID", currentID);
            data.append("currentPhone", currentPhone);
            data.append("empID", empID);
            data.append("empFirstName", empFirstName);
            data.append("empLastName", empLastName);
            data.append("empCity", empCity);
            data.append("empStreet", empStreet);
            data.append("empBirthdate", empBirthdate);
            data.append("empPhone", empPhone);
            data.append("empPwd", empPwd);
            data.append("empUpdate", "");
            const response = await fetch('../../includes/employee.inc.php', {
                method: 'POST',
                body: data
            })
            const info = await response.text();
            console.log(info);
            location.href = info;
        }

        function showSearchTable(sel) { // done
            let data = {
                "currentAdmin": "<?=$_SESSION['admin_name'];?>",
                "value": sel.value
            }
            fetch('employeeSearch.php', {
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
            const response = await fetch('employeeUpdateForm.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json; charset=utf-8'
                },
                body: JSON.stringify(data)
            })
            const info = await response.text();
            table.innerHTML = info;
        }

        function deleteEmployeeData(sel) { // done
            let confirmation = confirm('Are you sure you want to delete this?');
            if(confirmation) {
            data = new FormData();
            data.append("empDelete", sel)

            fetch('../../includes/employee.inc.php', {
                method: 'POST',
                body: data
            }).then(response=>{
                console.log(response);
                showTableForm();
            }).catch(error=>{
                console.warn(error);
            })
        }
    }
        async function showTableForm() { // done
            let currentUser = "<?=$_SESSION['admin_name'];?>";
            let currentData = {
                "currentAdmin": currentUser
            }

            try {
            const response = await fetch('employeeTableUpdater.php', {
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

        function showAddForm() { // done
        fetch('employeeAddForm.php')
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