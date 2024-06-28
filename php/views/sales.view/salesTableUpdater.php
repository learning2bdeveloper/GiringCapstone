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

    $query = "SELECT * FROM `sales` WHERE `Owner_admin_name_FK` = :boss;";
    $stmt = $Database->connect()->prepare($query);
    $stmt->bindParam(':boss', $data);
    $stmt->execute();

    $number_of_records_in_the_database = $stmt->rowCount();

    // Calculate the starting record index
    $startFrom = ($currentPage - 1) * $recordsPerPage;

    // Fetch student data with pagination

    $queryLimit = "SELECT * FROM `sales` WHERE `Owner_admin_name_FK` = :boss ORDER BY `ID` DESC LIMIT $startFrom, $recordsPerPage;";
    $query2 = $stmt2 = $Database->connect()->prepare($queryLimit);
    $query2->bindParam(':boss', $postedData['currentAdmin']);
    $query2->execute();
    $fetch = $query2->fetchAll();
    $result = $query2->rowCount();

    $queryCalculations = "SELECT COUNT(*) AS total_sales_order, SUM(`total`) AS total_sales_amount FROM `sales` WHERE `Owner_admin_name_FK` = :boss;";
    $stmtCalculations = $Database->connect()->prepare($queryCalculations);
    $stmtCalculations->bindParam(':boss', $postedData['currentAdmin']);
    $stmtCalculations->execute();
    $datas;
    while ($row = $stmtCalculations->fetch()) {
        $datas = $row;
    }

    if ($result > 0) {

        echo '<table>
            <thead>
                <tr>
                    <th>Total Sales Order</th>
                    <th>Total Sales Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>' . $datas['total_sales_order'] . '</td>
                    <td>' . "₱ " . number_format($datas['total_sales_amount']) . '</td>
                </tr>
            </tbody>
        </table>';

        $table = '<table>
        <tr>

        <th>Date</th>



        <th>Item Name</th>
        <th>Item Description</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
        <th>Sales ID</th>
        <th>Employee ID</th>
        <th>Employee Name</th>
        <th>Operations</th>
        </tr>';
        $number = 1;
        foreach ($fetch as $datas) {
            $id = $datas['ID'];
            $salesID = $datas['salesID'];
            $date = $datas['date'];
            $empID = $datas['empID_FK'];
            $empName = $datas['empID_Name'];
            $item_name = $datas['item_name'];
            $item_description = $datas['description'];
            $quantity = $datas['quantity'];
            $total = $datas['total'];
            $amount = $datas['amount'];
            $table .= '<tr>
            <td>' . $date . '</td>



            <td>' . $item_name . '</td>
            <td>' . $item_description . '</td>
            <td>' . "₱ " . number_format($amount) . '</td>
            <td>' . $quantity . '</td>
            <td>' . "₱ " . number_format($total) . '</td>
            <td>' . $salesID . '</td>
            <td>' . $empID . '</td>
            <td>' . $empName . '</td>
            <td>
            <button onclick="return confirm(\'Are you sure you want to delete this?\') ? deleteSalesData(' . $id . ') : null" class="text-button3"><a>DELETE</a></button>
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
