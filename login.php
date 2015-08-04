<?php
session_start(); // Our custom secure way of starting a PHP session.
include 'mod/setting.php';
require_once ('class/database_class.php');
require_once ('class/function_class.php');
require_once ('class/enkripsi_class.php');
require_once ('class/printer_class.php');

$db = new db($dbserver, $dbuser, $dbpass, $dbname);
$objFunction = new myfunction();
$objEnkrip = new Encryption();
$printer = new printer();

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
if (isset($_GET['ask']) && $_GET['ask'] == 'logout') {
    $objFunction->logout();
}

if (isset($_GET['ask']) && $objEnkrip->decode($_GET['ask']) == 'closing') {
    $iduser = $objEnkrip->decode($_GET['id']);
    //echo $iduser;
    $struk = '';
    $struk .= $printer->PrintHeader();
    $struk .= "Operator: " . $db->get_curr_data($db, 'user', 'nama', 'id_user = "' . $iduser . '"') . "\r\n";
    $struk .= "Login   : " . $db->get_curr_data($db, 'user', 'last_login', 'id_user = "' . $iduser . '"') . "\r\n";
    $struk .= "Closing : " . $objFunction->get_datetime_sql() . "\r\n\r\n";
    $grand_total = $db->get_data($db, 'transaksi', 'SUM(total_harga) as grand_total', ' datetime BETWEEN CURDATE() AND CURDATE() + INTERVAL 1 DAY AND id_user="' . $iduser . '"', '', '');
    $struk .= $printer->BigText();
    $struk .= "Total Transaksi : " . $objFunction->set_rupiah($grand_total[0]['grand_total']) . "\r\n";
    $struk .= $printer->TextNormal();
    $struk .= $printer->PrintEnter();
    $struk .= $printer->PrintEnter();
    $struk .= $printer->PrintEnter();
    $struk .= $printer->PrintEnter();
    $struk .= $printer->PrintEnter();
    $struk .= $printer->PrintEnter();
    $struk .= $printer->CutPaper();
    $setting = $db->get_data($db, 'setting', '*', '', '', '');
    @$printer->print_data($setting[0]['port'], $struk);
    $objFunction->logout();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="images/favicon.png">
        <title>Login</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/bootstrap-reset.css" rel="stylesheet">
        <link href="font/css/font-awesome.css" rel="stylesheet" />
        <link href="css/style.css" rel="stylesheet">
        <link href="css/style-responsive.css" rel="stylesheet" />

    </head>
    <body class="login-body">
        <div class="col-sm-12">
            <form class="form-signin" action="login.php" method="post">
                <h2 class="form-signin-heading"><img style="height: 80px;" alt="Logo" src="images/logo.png"></h2>
                <div class="login-wrap">
                    <div class="user-login-info">
                        <input type="text" name="username" class="form-control" placeholder="Username" autofocus>
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>
                    <button class="btn btn-lg btn-danger btn-block" type="submit"><b>Login</b></button>
                </div>
            </form>
        </div>
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
