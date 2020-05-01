<?php
//dev display errors
ini_set('display_errors', 1);
session_start();
if (!isset($_SESSION['user_id'])){
  header('Location: http://localhost/WebApp-Budget/website/Views/login.php');
}
require_once('../Models/transactionDAO.php');
$transDAO = new transactionDAO();

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans|Oswald&display=swap">
    <link rel="stylesheet" type="text/css" href="../resources/css/transView.css">
    <title>Transaction View Page</title>
  </head>
  <body>
    <div class="topnav">
      <span class="logo">think<span class="empower">Budget</span>
    </div>
    <div class="container">
      <div class="header">
        <h1>Transaction List</h1>
      </div>
      <div class="menu-bar">
        <?php
        echo '<a href=\'../Controllers/upload.php?user_id=' . $_SESSION['user_id'] . '\'><button type="button" name="import">Import from Data Dir</button></a>';
        ?>
      </div>
      <!-- <div class="fileUpload">
        <form class="upload" action="../Controllers/upload.php" method="post" enctype="multipart/form-data">
          <input type="file" name="csv" id="csv">
          <input type="submit" name="submit" value="Upload CSV">
        </form>
      </div> -->
      <?php
      if ($transactions = $transDAO->getRecordsByDate($_SESSION['user_id'])){
        echo '
        <div class="transactionList">
        <table>
        <thead>
          <tr>
            <th>Account</th>
            <th>Amount</th>
            <th>Description</th>
            <th>Payee</th>
            <th>Date</th>
            <th>Category</th>
            <th>Notes</th>
          </tr>
        </thead>
        <tbody>';
        $count = 0;
        foreach ($transactions as $trans){
          if ($count%2 == 0){
            echo '<tr style="background-color: #4d4d4d;">';
          } else{
            echo '<tr>';
          }

          echo '<td>' . $trans[2] . '</td>';
          if ($trans[3]<0){
            echo '<td style="color: #ff6666;">' . $trans[3] . '</td>';
          } else {
            echo '<td style="color: #00cc00;">' . $trans[3] . '</td>';
          }
          echo '<td>' . $trans[4] . '</td>';
          echo '<td>' . $trans[5] . '</td>';
          echo '<td>' . $trans[6] . '</td>';
          echo '<td>' . $trans[8] . '</td>';
          echo '<td style="min-width:40px;">' . $trans[7] . '</td></tr>';
          $count++;
        }
        echo '</tbody></table></div>';
      } else{
        echo '<div class="filler">No transactions on file</div>';
      }
      ?>
      <h2>
        Category Updater
        <?php echo '(' . $transDAO->countUnsortedPayees($_SESSION['user_id']) . ')'; ?>
      </h2>
      <div class="form">
        <form class="cat-update" id="cat-update" action="../Controllers/updateCats.php" method="get">
          <table>
            <tr>
              <td>
                <label for="payee-selector">Payee Selector</label>
                <select class="payeeList" name="payee-selector">
                  <?php
                  $unsortedPayees = $transDAO->getUnsortedPayees($_SESSION['user_id']);
                  foreach ($unsortedPayees as $i){
                    foreach ($i as $option){
                      echo '<option>' . $option . '</option>';
                    }
                  }
                  ?>
                </select>
              </td>
              <td>
                <label for="cat-selector">Category Selector</label>
                <select class="catList" name="cat-selector">
                  <option>Monthly Expense: Rent</option>
                  <option>Monthly Expense: Electricity</option>
                  <option>Everyday Expense: Groceries</option>
                  <option>Everyday Expense: Gas</option>
                  <option>Everyday Expense: Clothes</option>
                  <option>Everyday Expense: Eating Out</option>
                  <option>Misc Expense: Bills</option>
                  <option>Misc Expense: Repair/Home</option>
                  <option>Misc Expense: Entertainment</option>
                  <option>Misc Expense: Gifts</option>
                  <option>Misc Expense: Misc</option>
                  <option>Misc Expense: School</option>
                  <option>Misc Expense: Health</option>
                  <option>Transfer: Savings</option>
                  <option>Transfer: Misc</option>
                  <option>Income: Work</option>
                  <option>Income: Misc</option>
                </select>
              </td>
              <td><input type="submit" name="submit" value="Confirm"></td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </body>
</html>
