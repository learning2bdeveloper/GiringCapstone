<?php
require '../../classes/database.classes.php';
require '../../classes/budget.classes.php';

$postedData = json_decode(file_get_contents('php://input'),true);

$budget = new Budget;
$fetch = $budget->show_budget_data_by_id($postedData['id']);

$data;
while($datas = $fetch->fetch()) {
    $data = $datas;
}
        $table = '
        <div><br>
        <label for="category"><h3>Category</h3></label>
        <input type="text" id="category" class="custom-input" placeholder="Add Category" value="'.$data['Category'].'">
        </div>
        <br>
        <br>
        <br>
        <div>
            <textarea  id="description" class="custom-input" cols="30" rows="10">'.$data['Description'].'</textarea>
        </div>
        <br>
        <br>
        <br>
        <div>
        <label for="amount"><h3>Amount</h3></label>
                <input type="text" id="amount" class="custom-input" class="custom-input" placeholder="Enter Amount" value="'.$data['Amount'].'">
        </div>
        <br>
        <br>
        <br>
        <button type="submit" onclick="updateInventory('.$data['ID'].', \''.$data['Category'].'\')"class="text-button"><a>Update</a></button>';
        echo $table;
