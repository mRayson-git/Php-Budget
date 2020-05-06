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
  public function getRecordsByDate($user_id){
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
  public function getUnsortedPayees($user_id){
    $transactions = array();
    $query = 'SELECT DISTINCT trans_payee FROM transaction_t WHERE user_id = ? AND trans_category = \'Unsorted\'';
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
  public function countUnsortedPayees($user_id){
    $query = 'SELECT DISTINCT COUNT(trans_payee) FROM transaction_t WHERE user_id = ? AND trans_category = \'Unsorted\'';
    $stmt = $this->mysqli->prepare($query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $result = $result->fetch_row()[0];
    return $result;
  }

  public function updateRecords($user_id, $payee){
    $query = 'SELECT trans_category FROM transaction_t WHERE user_id = ? AND trans_payee = ? and trans_category != \'Unsorted\'';
    $stmt = $this->mysqli->prepare($query);
    $stmt->bind_param('is', $user_id, $payee);
    $stmt->execute();
    $result = $stmt->get_result();
    $result = $result->fetch_row()[0];
    if ($result){
      $query = 'UPDATE transaction_t SET trans_category = ? WHERE $payee =? AND trans_category == \'Unsorted\'';
      $stmt = $this->mysqli->prepare($query);
      $stmt->bind_param('ss', $result, $payee);
      $stmt->execute();
      if ($stmt->error){
        echo $stmt->error;
      } else {
        return true;
      }
    }
  }
  public function setCat($user_id, $trans_payee, $category){
    $trans_payee = $trans_payee;
    $query = 'UPDATE transaction_t SET trans_category = ? WHERE user_id = ? AND trans_payee = ?';
    $stmt = $this->mysqli->prepare($query);
    $stmt->bind_param('sis',$category,$user_id,$trans_payee);
    $stmt->execute();
    if ($stmt->error){
      echo $stmt->error;
    } else {
      return true;
    }
  }

}
?>
