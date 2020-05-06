<?php
//dev display errors
ini_set('display_errors', 1);

require_once('../Models/Record.php');
require_once('../Models/transactionDAO.php');
//script
if (!file_exists('../resources/data/lastRun.json')){
  $config = fopen('../resources/data/lastRun.json', 'wa+');
  $lastRun = array('1999-01-01');
  fwrite($config, json_encode($lastRun));
  fclose($config);
}
$hadNewRecords = false;

if (file_exists('../resources/data/pcbanking.csv')){
  $scotiaArray = array();
  $file = fopen('../resources/data/pcbanking.csv', 'r');
  while (($data = fgetcsv($file, 1000, ",")) !== FALSE){
    $transaction = new Record($_GET['user_id'], 'ScotiaChecking', $data[1], cleanString($data[4]), $data[3], fixDate($data[0]));
    array_push($scotiaArray, $transaction);
  }
  rename('../resources/data/pcbanking.csv', '../resources/data/oldfiles/pcbanking.csv');
  addRecords($scotiaArray);
  $hadNewRecords = true;
}

if (file_exists('../resources/data/report.csv')){
  $pcfinancialArray = array();
  $file = fopen('../resources/data/report.csv', 'r');
  fgetcsv($file);
  while (($data = fgetcsv($file, 1000, ",")) !== FALSE){
    $transaction = new Record($_GET['user_id'], 'PCFinancial', $data[5], cleanString($data[0]), $data[1], fixDate($data[3]));
    array_push($pcfinancialArray, $transaction);
  }
  rename('../resources/data/report.csv', '../resources/data/oldfiles/report.csv');
  addRecords($pcfinancialArray, $transaction);
  $hadNewRecords = true;
}

if ($hadNewRecords){
  $today = array(date('Y-m-d'));
  $config = fopen('../resources/data/lastRun.json', 'wa+');
  fwrite($config, json_encode($today));
  fclose($config);
}

header('Location: http://localhost/WebApp-Budget/website/Views/transView.php');


//Helper functions
function addRecords($transArray){
  $transDAO = new transactionDAO();
  $lastRun = json_decode(file_get_contents('../resources/data/lastRun.json'), true)[0];
  foreach ($transArray as $trans){
    if (strtotime($trans->getDate()) >= $lastRun){
      $transDAO->addRecord($trans);
      $transDAO->updateRecords($_GET['user_id'],$trans->getPayee());
    }
  }
}

function fixDate($date){
  $date = explode('/', $date);
  $fixedDate = $date[2] . '-' . $date[0] . '-' . $date[1];
  return $fixedDate;
}

function cleanString($oldString){
  $oldString = str_split($oldString);
  $newString = array();
  $i = 0;
  while ($i < count($oldString)){
    if ($oldString[$i] == ' ' && $oldString[$i+1] == ' '){
      return implode($newString);
    }
    array_push($newString, $oldString[$i]);
    $i++;
  }
  return implode($newString);
}
?>
