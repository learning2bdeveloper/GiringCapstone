<?php

require '../../classes/database.classes.php';

$Database = new Database;
$currentDate = date("Y-m-d");
$sql2 = "SELECT * FROM `active_expense` WHERE `Owner_admin_name_FK` = :boss AND `Due` = :currentdate";
$stmt2 = $Database->connect()->prepare($sql2);
$stmt2->bindParam('boss', $_SESSION['admin_name']);
$stmt2->bindParam('currentdate', $currentDate);
$stmt2->execute();

$sql = "SELECT `Message` FROM `notifications` WHERE `Owner_admin_name_FK` = :boss LIMIT 10 ";
$stmt = $Database->connect()->prepare($sql);
$stmt->bindParam(':boss', $_SESSION['admin_name']);
$stmt->execute();

while ($row = $stmt2->fetch()) {

    $message = "Due: " . $row['Due'] . " Payee: " . $row['Payee'] . "\n" . " Balance: " . $row['Amount'];
    $sql3 = "INSERT INTO `notifications`(`Owner_admin_name_FK`, `Message`) VALUES (:boss, :msg) ORDER BY `ID` DESC";
    $stmt3 = $Database->connect()->prepare($sql3);
    $stmt3->bindParam(':boss', $_SESSION['admin_name']);
    $stmt3->bindParam(':msg', $message);
    $stmt3->execute();
}

?>

<li><a href="./../../../home.php">HOME</a></li>
<li><a href="./../../../dashboard.php">DASHBOARD</a></li>
<!-- <li><a href="./../../../records.php">RECORDS</a></li> -->
<li><a href="./../../../about.php">ABOUT</a></li>
<li><a href="./../../../index.php?logout=true">LOGOUT</a></li>
<li><label for="notifications">Notification:</label></li>
<li><select name="" id="notifications">
<?php while ($data = $stmt->fetch()) {
    echo '<option value="' . $data['Message'] . '">' . $data['Message'] . '</option>';
}?>
</select></li>
