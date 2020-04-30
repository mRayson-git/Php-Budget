<?php
require_once('abstractDAO.php');
require_once('Record.php');

class transactionDAO extends abstractDAO{
  function __construct(){
    try{
      parent::__construct();
    } catch(Exception $e){
      throw $e;
    }
  }
  public function addRecord($record){
    $userId = $record->getUserId();
    $account = $record->getAccount();
    $amount = $record->getAmount();
    $payee = $record->getPayee();
    $description = $record->getDesc();
    $date = $record->getDate();
    $query = 'INSERT INTO transaction_t(user_id, trans_account, trans_amount, trans_payee, trans_desc, trans_date)
      VALUES (?,?,?,?,?,?)';
    $stmt = $this->mysqli->prepare($query);
    $stmt->bind_param('isdsss',
      $userId,
      $account,
      $amount,
      $payee,
      $description,
      $date);
    $stmt->execute();
    if ($stmt->error){
      echo $stmt->error;
    } else {
      return true;
    }
  }
  public function getRecords($user_id){
    $transactions = array();
    $query = 'SELECT * FROM transaction_t WHERE user_id = ? ORDER BY trans_date desc';
    $stmt = $this->mysqli->prepare($query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $result = $result->fetch_all();
    foreach ($result as $transaction){
      array_push($transactions, $transaction);
    }
    return $transactions;
  }
}
?>
