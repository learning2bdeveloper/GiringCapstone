<?php
include '../../classes/database.classes.php';
include '../../classes/stockcontrol.classes.php';

$postedData = json_decode(file_get_contents('php://input'), true);

if (isset($postedData)) {

    $data = $postedData['currentAdmin'];

    if (isset($postedData['page'])) {
        $currentPage = $postedData['page'];
    } else {
        $currentPage = 1;
    }

    $recordsPerPage = 5;

    $Database = new Database;

    $query = "SELECT * FROM `stockcontrol` WHERE `Owner_admin_name_FK` = :boss;";
    $stmt = $Database->connect()->prepare($query);
    $stmt->bindParam(':boss', $data);
    $stmt->execute();

    $number_of_records_in_the_database = $stmt->rowCount();

    // Calculate the starting record index
    $startFrom = ($currentPage - 1) * $recordsPerPage;

    // Fetch student data with pagination

    $queryLimit = "SELECT * FROM `stockcontrol` WHERE `Owner_admin_name_FK` = :boss ORDER BY `ID` DESC LIMIT $startFrom, $recordsPerPage;";
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
        <th>Item No</th>
        <th>Item Name</th>
        <th>Item Description</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Amount</th>
        <th>Tax Rate</th>
        <th>Tax</th>
        <th>Total</th>
        <th>Date Updated</th>
        <th>Operations</th>
        </tr>';
        foreach ($fetch as $datas) {
            $table .= '<tr>
            <td>' . $datas['empID_FK'] . '</td>
            <td>' . $datas['date'] . '</td>
            <td>' . $datas['item_no'] . '</td>
            <td>' . $datas['item_name'] . '</td>
            <td>' . $datas['item_description'] . '</td>
            <td>' . $datas['price'] . '</td>
            <td>' . $datas['quantity'] . '</td>
            <td>' . "₱ " . $datas['amount'] . '</td>
            <td>' . $datas['tax_rate'] . '</td>
            <td>' . $datas['tax'] . '</td>
            <td>' . $datas['total'] . '</td>
            <td>' . $datas['modified_date'] . '</td>
            <td>
            <button onclick="deletestockcontrolData(' . $datas['ID'] . ',\'' . $datas['item_no'] . '\',\'' . $datas['quantity'] . '\')"class="text-button3"><a>Delete</a></button>
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
