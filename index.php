<?php
session_start();
//Include
include 'mod/setting.php';
require_once ('class/database_class.php');
require_once ('class/printer_class.php');
require_once ('class/function_class.php');
require_once ('class/enkripsi_class.php');

//Objek
$db = new db($dbserver, $dbuser, $dbpass, $dbname);
$objFunction = new myfunction();
$objEnkrip = new Encryption();
$printer = new printer();

$objFunction->checkLogin();
?>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="tonx">
        <link rel="shortcut icon" href="images/favicons.png">
        <title>TekBow</title>

        <link href="css/bootstrap.min.css" rel="stylesheet">

        <link href="css/bootstrap-reset.css" rel="stylesheet">
        <link href="font/css/font-awesome.css" rel="stylesheet">
        <link href="css/clndr.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link href="css/style-responsive.css" rel="stylesheet"/>
        <link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
        <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css" />

        <script src="js/jquery-1.8.3.min.js"></script>
        
        <script src="js/jquery.nicescroll.js"></script>
        <script src="js/scripts.js"></script>
        <script src="js/select2/select2.js"></script>
        <script src="js/jquery.datetimepicker.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.base64.min.js"></script>
        
        <link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css" />
        <script src="js/jquery.datetimepicker.js"></script>
       


    </head>
    <body>
        <section id="container">
            <?php
            $reqMenu = (isset($_REQUEST['ask']) && trim($_REQUEST['ask']) != '' ? $objEnkrip->decode($_REQUEST['ask']) : 'dtpos.php');

            include 'mod/top_nav.php';
            ?>
            <section >
                <section class="wrapper">
                    <?php
                    if ($reqMenu != '') {
                        include 'mod/' . $reqMenu;
                    }
                    ?>
                </section>

            </section>
    </body>


</html>

