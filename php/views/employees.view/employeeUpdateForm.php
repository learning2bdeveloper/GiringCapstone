<?php

require '../../classes/database.classes.php';
require '../../classes/employee.classes.php';

$postedData = json_decode(file_get_contents('php://input'),true);
$empID = $postedData['empID'];

$Employee = new Employee;
$fetchingData = $Employee->get_employees_data($empID);
$data;

foreach($fetchingData as $datas) {
    $data = $datas;
}

 $table = '
    <table>
        <tr>
            <th>EMPLOYEE ID</th>
            <th>FIRST NAME</th>
            <th>LAST NAME</th>
            <th>CITY</th>
            <th>STREET</th>
            <th>BIRTHDATE</th>
            <th>PHONE</th>
            <th>PASSWORD</th>
        </tr>
      
        <tr>
            <td><input type="text" name="empID" value="'.$data['empID'].'"></td> 
            <td><input type="text" name="empFirstName" value="'.$data['first_name'].'"></td> 
            <td><input type="text" name="empLastName" value="'.$data['last_name'].'"></td> 
            <td><select name="empCity"> './*gin butangan ternary expression para mag gana haha*/'
            <option value="Bacolod City"'.(($data['city'] === "Bacolod City") ? "selected":"").'>Bacolod City</option>
            <option value="Bago City"'.(($data['city'] === "Bago City") ? "selected":"").'>Bago City</option>
            <option value="Murcia City"'.(($data['city'] === "Murcia City") ? "selected":"").'>Murcia City</option>
            <option value="Silay City"'.(($data['city'] === "Silay City") ? "selected":"").'>Silay City</option>
            <option value="Talisay City"'.(($data['city'] === "Talisay City") ? "selected":"").'>Talisay City</option>
            <option value="La Carlota City"'.(($data['city'] === "La Carlota City") ? "selected":"").'>La Carlota City</option>
            <option value="Valladolid City"'.(($data['city'] === "Valladolid City") ? "selected":"").'>Valladolid City</option>
            <option value="E.B. Magallona City"'.(($data['city'] === "E.B. Magallona City") ? "selected":"").'>E.B. Magallona City</option>
            <option value="Victorias City"'.(($data['city'] === "Victorias City") ? "selected":"").'>Victorias City</option>
            </select></td>
            <td><input type="text" name="empStreet" value="'.$data['street'].'"></td>
            <td><input type="date" name="empBirthdate" value="'.$data['birth_date'].'"></td>
            <td><input type="number" name="empPhone" value="'.$data['phone'].'"></td>
            <td><input type="text" name="empPwd" value="'.$data['password'].'"></td>    
        </tr>
    </table>
    <button type="submit" onclick="updateEmployee(\''.$data['empID'].'\','.$data['phone'].')"class="text-button1"><a>Update</a></button>';
    echo $table;    

