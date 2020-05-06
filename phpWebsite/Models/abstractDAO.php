<?php
class abstractDAO{
  protected $mysqli;
  protected static $DB_HOST = "localhost";
  protected static $DB_USERNAME = "budgetAdmin";
  protected static $DB_PASSWORD = "thinkBudg3tThinkMon3y";
  protected static $DB_DATABASE = "budgetApplication";

  function __construct(){
    try{
      $this->mysqli = new mysqli(self::$DB_HOST, self::$DB_USERNAME, self::$DB_PASSWORD, self::$DB_DATABASE);
    }catch(mysqli_sql_exception $e){
      throw $e;
    }
  }

  public function getMysqli(){
    return $this->mysqli;
  }
}
?>
