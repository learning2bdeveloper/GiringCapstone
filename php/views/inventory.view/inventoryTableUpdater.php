<?php
require '../../classes/database.classes.php';

$postedData = json_decode(file_get_contents('php://input'), true);

if (isset($postedData)) {

    if (isset($postedData['page'])) {
        $currentPage = $postedData['page'];
    } else {
        $currentPage = 1;
    }

    $recordsPerPage = 5;

    $data = $postedData['currentAdmin'];

    $Database = new Database;

    $query = "SELECT * FROM `inventory` WHERE `Owner_admin_name_FK` = :boss;";
    $stmt = $Database->connect()->prepare($query);
    $stmt->bindParam(':boss', $data);
    $stmt->execute();

    $number_of_records_in_the_database = $stmt->rowCount();

    // Calculate the starting record index
    $startFrom = ($currentPage - 1) * $recordsPerPage;

    // Fetch student data with pagination

    $queryLimit = "SELECT * FROM `inventory` WHERE `Owner_admin_name_FK` = :boss ORDER BY `ID` DESC LIMIT $startFrom, $recordsPerPage;";
    $query2 = $stmt2 = $Database->connect()->prepare($queryLimit);
    $query2->bindParam(':boss', $postedData['currentAdmin']);
    $query2->execute();
    $fetch = $query2->fetchAll();
    $result = $query2->rowCount();

    if ($result > 0) {
        $table = '<table>
        <tr>
        <th>Employee ID</th>
        <th>Date</th>
        <th>Item No.</th>
        <th>Item Name</th>
        <th>Item Description</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Amount</th>
        <th>Date Modified</th>
        <th>Operations</th>
        </tr>';
        $number = 1;
        foreach ($fetch as $datas) {
            $id = $datas['ID'];
            $employeeID = $datas['empID_FK'];
            $date = $datas['date'];
            $item_no = $datas['item_no'];
            $item_name = $datas['item_name'];
            $item_description = $datas['item_description'];
            $quantity = $datas['quantity'];
            $price = $datas['price'];
            $amount = $datas['amount'];
            $date_modified = $datas['modified_date'];
            $table .= '<tr>
            <td>' . $employeeID . '</td>
            <td>' . $date . '</td>
            <td>' . $item_no . '</td>
            <td>' . $item_name . '</td>
            <td>' . $item_description . '</td>
            <td>' . $quantity . '</td>
            <td>' . $price . '</td>
            <td>' . "₱ " . number_format($amount) . '</td>
            <td>' . $date_modified . '</td>
            <td>
            <button onclick="showUpdateForm(' . $id . ')" class="text-button2"><a>UPDATE</a></button>
            <button onclick="deleteInventoryData(' . $id . ',' . $amount . ')"  onclick = "return confirm(\'Are you sure you want to delete this?\')"class="text-button3"><a>DELETE</a></button>
            </td>
            </tr>';
        }
        $table .= '</table>';
        echo $table;
    } else {
        echo "No records found!";
    }

    # Pagination links

    $totalPages = ceil($number_of_records_in_the_database / $recordsPerPage);

    if ($currentPage > 1) { # Previous
        echo '<a onclick="showTableForm(' . ($currentPage - 1) . ')">Previous</a>';
    }

    if ($totalPages > 1) {
        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $currentPage) {
                echo '<a onclick="showTableForm(' . $i . ')">' . $i . '</a>';
            } else {
                echo '<a onclick="showTableForm(' . $i . ')">' . $i . '</a>';
            }
        }
    }

    if ($currentPage < $totalPages) { # next
        echo '<a onclick="showTableForm(' . ($currentPage + 1) . ')">Next</a>';
    }
    echo '<p>Page ' . $currentPage . ' of ' . $totalPages . ' Pages</p>';
}
