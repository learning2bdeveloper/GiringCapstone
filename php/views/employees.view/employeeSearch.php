<?php
require '../../classes/database.classes.php';
require '../../classes/employee.classes.php';
$postedrow = json_decode(file_get_contents('php://input'), true);
$data = $postedrow['value'];
$currentAdmin = $postedrow['currentAdmin'];

$table;

$Employee = new Employee;
$stmt = $Employee->search_employee_table($data, $currentAdmin);

$number = 1;
$table = '<table>
        <thead>
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
            </tr>
        </thead>';
while ($row = $stmt->fetch()) {
    $formattedNumber = substr($row['phone'], 0, 3) . '-' . substr($row['phone'], 3, 3) . '-' . substr($row['phone'], 6);
    $table .= '<tbody>
            <tr>
                <td style="color: ' . ($row['status'] === "Offline" ? "red" : "green") . ' ;font-weight: bold; ">' . $row['status'] . '</td>
                <td>' . $row['empID'] . '</td>
                <td>' . $row['first_name'] . '</td>
                <td>' . $row['last_name'] . '</td>
                <td>' . $row['city'] . '</td>
                <td>' . $row['street'] . '</td>
                <td>' . $row['birth_date'] . '</td>
                <td>' . $formattedNumber . ' </td>
                <td>' . $row['hired_date'] . '</td>
                <td>
                <button onclick="showUpdateForm(' . $row['empID'] . ')"class="text-button2"><a>Update</a></button>
                <button onclick="deleteEmployeeData(\'' . $row['empID'] . '\')"class="text-button3"><a>Delete</a></button>
                </td>
            </tr>
       </tbody>';
    $number++;
}
$table .= '</table>';
echo $table;
