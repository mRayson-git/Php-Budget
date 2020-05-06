<?php
require_once('abstractDAO.php');

class userDAO extends abstractDAO{
  function __construct(){
    try{
      parent::__construct();
    }catch(Exception $e){
      throw $e;
    }
  }
  public function checkExists($userName){
    $query = 'SELECT user_id FROM user WHERE username = ?';
    $stmt = $this->mysqli->prepare($query);
    $stmt->bind_param('s', $userName);
    $stmt->execute();
    if ($stmt->error){
      return $stmt->error;
    }
    $stmt->bind_result($exists);
    while ($stmt->fetch()){
      return true;
    }
    return false;
  }
  public function validateUser($userName, $userPassword){
    if ($this->checkExists($userName)){
      $query = 'SELECT password FROM user WHERE username = ?';
      $stmt = $this->mysqli->prepare($query);
      $stmt->bind_param('s', $userName);
      $stmt->execute();
      $result = $stmt->get_result();
      $passOnFile = $result->fetch_assoc()['password'];
      if ($userPassword == $passOnFile){
        return true;
      } else {
        return false;
      }
    }
  }
  public function addUser($userName, $userPassword){
    $query = 'INSERT INTO user(username, password) VALUES (?,?)';
    $stmt = $this->mysqli->prepare($query);
    $stmt->bind_param('ss', $userName, $userPassword);
    $stmt->execute();
    if ($stmt->error){
      return $stmt->error;
    } else {
      return true;
    }
  }
  public function getUserId($userName){
    $query = 'SELECT user_id FROM user WHERE username = ?';
    $stmt = $this->mysqli->prepare($query);
    $stmt->bind_param('s', $userName);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['user_id'];
  }
}
?>
