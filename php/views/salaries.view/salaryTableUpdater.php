<?php session_start();
require '../../classes/database.classes.php';

$postedData = json_decode(file_get_contents("php://input"), true);
$data = $postedData['currentAdmin'];

$Database = new Database;

if (isset($postedData['page'])) {
    $currentPage = $postedData['page'];
} else {
    $currentPage = 1;
}

$recordsPerPage = 5;

$query = "SELECT * FROM `salary` WHERE `admin_name_FK` = :boss;";
$stmt = $Database->connect()->prepare($query);
$stmt->bindParam(':boss', $data);
$stmt->execute();

$number_of_records_in_the_database = $stmt->rowCount();

// Calculate the starting record index
$startFrom = ($currentPage - 1) * $recordsPerPage;

// Fetch student data with pagination

$queryLimit = "SELECT * FROM `salary` WHERE `admin_name_FK` = :boss ORDER BY `ID` DESC LIMIT $startFrom, $recordsPerPage;";
$query2 = $stmt2 = $Database->connect()->prepare($queryLimit);
$query2->bindParam(':boss', $postedData['currentAdmin']);
$query2->execute();
$fetch = $query2->fetchAll();
$result = $query2->rowCount();

if ($result > 0) {

    $table = '<table>
      <tr>
          <th>ID</th>
          <th>Employee ID</th>
          <th>PAY FREQUENCY</th>
          <th>RATE</th>
          <th>DAILY WORK HOURS</th>
          <th>WORK DAYS PER WEEK</th>
          <th>Salary</th>
          <th>Pay Date</th>
          <th>Operations</th>
      </tr>';
    $number = 1;
    foreach ($fetch as $datas) {
        $table .= '<tr>
        <td>' . $number . '</td>
        <td>' . $datas['empID_FK'] . '</td>
        <td>' . $datas['pay_frequency'] . '</td>
        <td>₱ ' . number_format($datas['rate']) . '</td>
        <td>' . $datas['daily_work_hours'] . '</td>
        <td>' . $datas['work_days_per_week'] . '</td>
        <td>₱ ' . number_format($datas['salary']) . '</td>
        <td>' . $datas['pay_date'] . '</td>
        <td>
        <button onclick="taxCalculations(\'' . $datas['pay_frequency'] . '\',\'' . $datas['rate'] . '\',\'' . $datas['daily_work_hours'] . '\',\'' . $datas['work_days_per_week'] . '\',\'' . $datas['empID_FK'] . '\')"class="text-button4"><a>+</a></button>
        <button onclick="showUpdateForm(\'' . $datas['empID_FK'] . '\')"class="text-button2"><a>Update</a></button>
        <button onclick="deleteSalaryData(\'' . $datas['empID_FK'] . '\')"class="text-button3"><a>Delete</a></button>
        <button onclick="paySalaryData(\'' . $datas['empID_FK'] . '\',\'' . $datas['pay_frequency'] . '\',\'' . $datas['salary'] . '\')"class="text-button4"><a>Pay</a></button>
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
