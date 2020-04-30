<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans|Oswald&display=swap">
        <link rel="stylesheet" type="text/css" href="../resources/css/login.css">
        <title>Login Page</title>
    </head>
    <body>
      <div class="container">
        <div class="opening">
            <h1>think<span class="empower">Budget</span></h1>
            <h3>think money.</h3>
        </div>
        <div class="login">
          <?php
          try{
            session_start();
            include('../Models/userDAO.php');
            $userDAO = new userDAO();

            //Form value checking
            $hasError = false;
            $errorMessages = array();
            if ((isset($_POST['username'])) && (isset($_POST['password']))){
              if ($_POST['username'] == ""){
                $errorMessages['usernameError'] = "Field cannot be blank";
                $hasError = true;
              }
              if (strpos($_POST['username'], ';') !== false){
                $errorMessages['usernameError'] = "Username cannot contain invalid charaters";
                $hasError = true;
              }
              if ((isset($_POST['signUp'])) && ($userDAO->checkExists($_POST['username']))){
                $errorMessages['usernameError'] = "Username already in use";
                $hasError = true;
              }
              if ((isset($_POST['signIn'])) && (!$userDAO->checkExists($_POST['username']))){
                $errorMessages['usernameError'] = "Username not on file";
                $hasError = true;
              }
              if ($_POST['password'] == ""){
                $errorMessages['passwordError'] = "Field cannot be blank";
                $hasError = true;
              }
              if (strpos($_POST['username'], ';') !== false){
                $errorMessages['passwordError'] = "Password cannot contain invalid charaters";
                $hasError = true;
              }
              if ((isset($_POST['signIn'])) && (!$userDAO->validateUser($_POST['username'],$_POST['password']))){
                $errorMessages['passwordError'] = "Incorrect password";
                $hasError = true;
              }
              //Session creation
              if (!$hasError){
                if (isset($_POST['signUp'])){
                  $userDAO->addUser($_POST['username'],$_POST['password']);
                }
                $_SESSION['user_id'] = $userDAO->getUserId($_POST['username']);
                header('Location: http://localhost/WebApp-Budget/website/Views/transView.php');
              }
            }

          }catch(Exception $e){
            echo '<p>' . $e->getMessage() . '</p>';
          }
          ?>
          <form method="POST" action="">
            <fieldset>
              <legend>Login/SignUp</legend>
              <table>
                <tr>
                  <td><input type="text" name="username" value="" placeholder="Enter Your Username" size="30"></td>
                  <td><?php if (isset($errorMessages['usernameError'])){echo "<span style='color: red;'>" . $errorMessages['usernameError'] . "</span>";}?></td>
                </tr>
                <tr>
                  <td><input type="password" name="password" value="" placeholder="Enter Your Password" size="30"></td>
                  <td><?php if (isset($errorMessages['passwordError'])){echo "<span style='color: red;'>" . $errorMessages['passwordError'] . "</span>";}?></td>
                </tr>
                <tr>
                  <td><button type="submit" name="signIn">Sign In</button><button type="submit" name="signUp">Sign Up</button></td>
                </tr>
              </table>
            </fieldset>
          </form>
        </div>
      </div>
    </body>
</html>
