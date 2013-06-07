<?php

    if($_POST){
        require_once 'config.php';

        try {
            $DBH= new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPW);
            $DBH->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);// ::TODO:: change it befor productive

            $STH = $DBH->prepare("SELECT uid FROM be_users WHERE username = :be_user AND password = :password");
            $STH->bindParam(':be_user', $be_user);
            $STH->bindParam(':password', $password);
            $_POST['password'] = md5($_POST['password']);
            $STH->execute($_POST);

            $be_user_uid = $STH->fetch();
        }
        catch(PDOException $e) 
        {
            echo $e->getMessage();
        }

        if ($be_user_uid['uid']){
            session_start();
            $_SESSION['uid'] = $be_user_uid['uid'];
            header('Location: qualit.php');
            exit;
        }
        else echo "Zugangsdaten falsch";
    }
?>

<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <title>Sign in &middot; typo3 Backenduser</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

  </head>

  <body>

    <div class="container">

      <form class="form-signin" action="<?=$_SERVER['PHP_SELF'] ?>" method="post">
        <h2 class="form-signin-heading">Bitte einlogen</h2>
        <input type="text" name="be_user" class="input-block-level" placeholder="typo3 Backend-User">
        <input type="password" name="password" class="input-block-level" placeholder="Passwort">
        <button class="btn btn-large btn-primary" type="submit">Sign in</button>
      </form>
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-2.0.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

  </body>
</html>
