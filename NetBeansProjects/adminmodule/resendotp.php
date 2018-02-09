<?php

/* Template Name:ResendOtp */


if (empty($_SERVER['CONTENT_TYPE'])) {
    $_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded";
}

$ThisScript = basename(__FILE__, '.php');
$libpath = dirname(__FILE__);
require_once(ABSPATH . '/dwpivr/common.php');
require_once('dwreglib.php');
initconfig();
//connecton will be created
createConnection();
$regmobile = $_REQUEST["v_regmobile"];
$dwempid = $_REQUEST["v_dwempid"];
createOTP($regmobile);


echo "<script>alert('Resend Otp Successfully ');document.location='".site_url()."/empreg2 ?v_dwempid=".$dwempid."&v_regmobile=".$regmobile."';</script>";

?>