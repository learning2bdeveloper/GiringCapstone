<?php session_start();
include '../../classes/database.classes.php';

$postedData = json_decode(file_get_contents('php://input'), true);

if (isset($postedData['page'])) {
    $currentPage = $postedData['page'];
} else {
    $currentPage = 1;
}

$recordsPerPage = 5;

$data = $postedData['currentAdmin'];

$Database = new Database;

$query = "SELECT * FROM `employee` WHERE `Owner_admin_name_FK` = :boss;";
$stmt = $Database->connect()->prepare($query);
$stmt->bindParam(':boss', $data);
$stmt->execute();

$number_of_records_in_the_database = $stmt->rowCount();

// Calculate the starting record index
$startFrom = ($currentPage - 1) * $recordsPerPage;

// Fetch student data with pagination

$queryLimit = "SELECT * FROM `employee` WHERE `Owner_admin_name_FK` = :boss ORDER BY `ID` DESC LIMIT $startFrom, $recordsPerPage;";
$query2 = $stmt2 = $Database->connect()->prepare($queryLimit);
$query2->bindParam(':boss', $postedData['currentAdmin']);
$query2->execute();
$fetch = $query2->fetchAll();
$result = $query2->rowCount();

if ($result > 0) {
    $table = '<table>
                    <tr>
                        <th>Status</th>
                        <th>EmployeeID</th>
                        <th>FIRSTNAME</th>
                        <th>LASTNAME</th>
                        <th>CITY</th>
                        <th>STREET</th>
                        <th>BIRTHDATE</th>
                        <th>PHONE</th>
                        <th>HIRED DATE</th>
                        <th>Operations</th>
                    </tr>';
    $number = 1;
    foreach ($fetch as $data) {
        $formattedNumber = substr($data['phone'], 0, 3) . '-' . substr($data['phone'], 3, 3) . '-' . substr($data['phone'], 6);
        $table .= '<tr>
                        <td style="color: ' . ($data['status'] === "Offline" ? "red" : "green") . ' ;font-weight: bold; ">' . $data['status'] . '</td>
                        <td>' . $data['empID'] . '</td>
                        <td>' . $data['first_name'] . '</td>
                        <td>' . $data['last_name'] . '</td>
                        <td>' . $data['city'] . '</td>
                        <td>' . $data['street'] . '</td>
                        <td>' . $data['birth_date'] . '</td>
                        <td>' . $formattedNumber . ' </td>
                        <td>' . $data['hired_date'] . '</td>
                        <td>
                        <button onclick="showUpdateForm(\'' . $data['empID'] . '\')"class="text-button2"><a>Update</a></button>
                        <button onclick="deleteEmployeeData(\'' . $data['empID'] . '\')"class="text-button3"><a>Delete</a></button>
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
