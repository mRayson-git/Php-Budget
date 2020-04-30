<?php
//dev display errors
ini_set('display_errors', 1);
session_start();
if (!isset($_SESSION['user_id'])){
  header('Location: http://localhost/WebApp-Budget/website/Views/login.php');
}
require_once('../Models/transactionDAO.php');
$transDAO = new transactionDAO();
if (isset($_POST['importFromDir'])){
  require_once('../Controllers/upload.php');
}

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
        echo '<a href=\'editTransactionView.php?user_id=' . $_SESSION['user_id'] . '\'><button type="button" name="edit">View Uncategorized Payees</button></a>';
        ?>
      </div>
      <!-- <div class="fileUpload">
        <form class="upload" action="../Controllers/upload.php" method="post" enctype="multipart/form-data">
          <input type="file" name="csv" id="csv">
          <input type="submit" name="submit" value="Upload CSV">
        </form>
      </div> -->
      <?php
      if ($transactions = $transDAO->getRecords($_SESSION['user_id'])){
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
          echo '<td>' . $trans[7] . '</td></tr>';
          $count++;
        }
        echo '</tbody></table></div>';
      } else{
        echo '<div class="filler">No transactions on file</div>';
      }
      ?>
    </div>
  </body>
</html>
