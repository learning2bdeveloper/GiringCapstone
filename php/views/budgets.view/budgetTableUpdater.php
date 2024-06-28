<?php
include '../../classes/database.classes.php';

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

    $query = "SELECT * FROM `budget` WHERE `Owner_admin_name_FK` = :boss;";
    $stmt = $Database->connect()->prepare($query);
    $stmt->bindParam(':boss', $data);
    $stmt->execute();

    $number_of_records_in_the_database = $stmt->rowCount();

    // Calculate the starting record index
    $startFrom = ($currentPage - 1) * $recordsPerPage;

    // Fetch student data with pagination

    $queryLimit = "SELECT * FROM `budget` WHERE `Owner_admin_name_FK` = :boss ORDER BY `ID` DESC LIMIT $startFrom, $recordsPerPage;";
    $query2 = $stmt2 = $Database->connect()->prepare($queryLimit);
    $query2->bindParam(':boss', $postedData['currentAdmin']);
    $query2->execute();
    $fetch = $query2->fetchAll();
    $result = $query2->rowCount();

    if ($result > 0) {
        $table = '<table>
        <tr>
        <th>ID</th>
        <th>Date</th>
        <th>Category</th>
        <th>Description</th>
        <th>Amount</th>
        <th>Date Updated</th>
        <th>Operations</th>
        </tr>';
        $number = 1;
        foreach ($fetch as $datas) {
            $table .= '<tr>
            <td>' . $number . '</td>
            <td>' . $datas['Date'] . '</td>
            <td>' . $datas['Category'] . '</td>
            <td>' . $datas['Description'] . '</td>
            <td>' . "₱ " . number_format($datas['Amount']) . '</td>
            <td>' . $datas['Update_date'] . '</td>
            <td>
            <button onclick="showUpdateForm(' . $datas['ID'] . ')"class="text-button2"><a>Update</a></button>
            <button onclick="deleteBudgetData(' . $datas['ID'] . ')"class="text-button3"><a>Delete</a></button>
            </td>
            </tr>';

            $number++;
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
