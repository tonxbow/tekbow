<?php
session_start(); // Our custom secure way of starting a PHP session.
include 'mod/setting.php';
require_once ('class/database_class.php');
require_once ('class/function_class.php');
require_once ('class/enkripsi_class.php');

$db = new db($dbserver, $dbuser, $dbpass, $dbname);
$objFunction = new myfunction();
$objEnkrip = new Encryption();

if (isset($_POST['username'], $_POST['password'])) {
    $username = mysql_escape_string($_POST['username']);
    $password = mysql_escape_string($_POST['password']); // The hashed password
    if ($objFunction->login($username, $password) == true) {
        $hrf = "index.php";
        ?>
        <script>
            window.location.href = "<?php echo $hrf; ?>";
        </script>
        <?php
    } else {
        ?>
        <script>
            alert('Username atau Password Salah');
        </script>
        <?php
    }
}
if (isset($_GET['ask']) && isset($_GET['ask']) == 'logout') {
    $objFunction->logout();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="ThemeBucket">
        <link rel="shortcut icon" href="images/favicon.png">
        <title>Login</title>
        <!--Core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-reset.css" rel="stylesheet">
        <link href="font/css/font-awesome.css" rel="stylesheet" />
        <!-- Custom styles for this template -->
        <link href="css/style.css" rel="stylesheet">
        <link href="css/style-responsive.css" rel="stylesheet" />
        <link href="css/custom/resize.css" rel="stylesheet">        
        <style>
            body.login-body {
                border: none !important;
            }

            .container {
                margin-left: -50px;
            }
        </style>

    </head>
    <body class="login-body">
        <div class="container" style="margin: 0;">

            <form class="form-signin" action="login.php" method="post">
                <h2 class="form-signin-heading"><img style="height: 80px;" alt="Logo" src="images/logo.png"></h2>
                <div class="login-wrap">
                    <div class="user-login-info">
                        <input type="text" name="username" class="form-control" placeholder="Username" autofocus>
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>
                    <button class="btn btn-lg btn-login btn-block" type="submit">Login</button>
                </form>
            </div>
        
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
