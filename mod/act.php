<?php

/* ==============================================================================
 * library
  ============================================================================== */
session_start();
include "db_function.php";
include "function.php";
require_once("mod_enkripsi.php");
checkLogin();


/* ==============================================================================
 *  processing "action"
  ============================================================================== */

$objEnkrip = new Encryption();
$reqAsk = (isset($_REQUEST['ask']) && trim($_REQUEST['ask']) != '' ? $objEnkrip->decode($_REQUEST['ask']) : '');
if ($reqAsk != '') {
    include '../action/' . $objEnkrip->decode($_REQUEST['ask']);
}
?>

