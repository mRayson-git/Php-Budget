<?php
class Record{
  private $user_id;
  private $trans_account;
  private $trans_amount;
  private $trans_payee;
  private $trans_date;
  private $trans_desc;

  function __construct($user, $account, $amount, $payee, $description, $date){
    $this->setUserId($user);
    $this->setAccount($account);
    $this->setAmount($amount);
    $this->setPayee($payee);
    $this->setDesc($description);
    $this->setDate($date);
  }
  public function setUserId($user){
    $this->user_id = $user;
  }
  public function getUserId(){
    return $this->user_id;
  }
  public function setAccount($account){
    $this->trans_account = $account;
  }
  public function getAccount(){
    return $this->trans_account;
  }
  public function setAmount($amount){
    $this->trans_amount = $amount;
  }
  public function getAmount(){
    return $this->trans_amount;
  }
  public function setPayee($payee){
    $this->trans_payee = $payee;
  }
  public function getPayee(){
    return $this->trans_payee;
  }
  public function setDate($date){
    $this->trans_date = $date;
  }
  public function getDate(){
    return $this->trans_date;
  }
  public function setDesc($description){
    $this->trans_desc = $description;
  }
  public function getDesc(){
    return $this->trans_desc;
  }
}
?>
