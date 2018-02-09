<?php
/*
  Template Name: paymentsuccess
 */
if (empty($_SERVER['CONTENT_TYPE'])) {
    $_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded";
}

$ThisScript = basename(__FILE__, '.php');
$libpath = dirname(__FILE__);
require_once(ABSPATH . '/dwpivr/common.php');


initconfig();
//connecton will be created
createConnection();

$paymentid=$_REQUEST['payment_id'];
$requestid=$_REQUEST['payment_request_id'];
//$dwempid=$_REQUEST['v_dwempid'];
//$curremp = getEMPbyID($dwempid);
 $contractid=$_REQUEST['v_contractid'];

$sql = "UPDATE `payment` SET `payment_id`='".$paymentid."',`payment_request_id`='".$requestid."' WHERE `_id`='".$requestid."'";
// [payment_id] => MOJO8131005A89673461 [payment_request_id] => 5565f632e1454745804487235c33d393 
$result = dwqueryexec(__FUNCTION__, $sql);
//echo "<script>alert('Payment is successfully completed.');document.location='".site_url()."/webhook?v_payid=".$paymentid;"'; </script>";
  
               

echo "<script>document.location='" .site_url() . "/webhook?v_payid=".$paymentid."&v_contractid=".$contractid."';</script>";

?>