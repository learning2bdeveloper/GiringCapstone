<?php
 $table = '<form action="../../includes/employee.inc.php" method="post">
    <table>
        <tr>
            <th>FIRST NAME</th>
            <th>LAST NAME</th>
            <th>CITY</th>
            <th>STREET</th>
            <th>BIRTHDATE</th>
            <th>PHONE</th>
            <th>PASSWORD</th>
        </tr>
      
        <tr>
            <td><input type="text" name="empFirstName"></td> 
            <td><input type="text" name="empLastName"></td> 
            <td><input type="text" name="empCity"></td>
            <td><input type="text" name="empStreet"></td>
            <td><input type="date" name="empBirthdate"></td>
            <td><input type="number" name="empPhone"></td>
            <td><input type="text" name="empPwd"></td>    
        </tr>
    </table>
    <button type="submit" name="empAdd" onclick = "return confirm(\'Are you sure you want to add this?\')"class="text-button1"><a>ADD EMPLOYEE</a></button>
    </form>';
    echo $table;    

