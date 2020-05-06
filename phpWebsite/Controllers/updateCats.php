<?php
session_start();
if (!isset($_SESSION['user_id'])){
  header('Location: http://localhost/WebApp-Budget/website/Views/login.php');
}
ini_set('display_errors', 1);
//first set the value for that $trans_id, then set for all the relating trans_id
require_once('../Models/transactionDAO.php');
$transDAO = new transactionDAO();
$transDAO->setCat($_SESSION['user_id'], $_GET['payee-selector'], $_GET['cat-selector']);
header('Location: http://localhost/WebApp-Budget/website/Views/transView.php');
?>
