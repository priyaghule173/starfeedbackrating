<?php

/*
  Template Name: paymentreqsub
 */
if (empty($_SERVER['CONTENT_TYPE'])) {
    $_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded";
}



$ThisScript = basename(__FILE__, '.php');
//Common Functions
require_once(ABSPATH . '/dwpivr/common.php');
initconfig();

createConnection();
$dwempid = $_REQUEST['v_dwempid'];
$contractid=$_REQUEST['v_contractid'];
$curremp = getEMPbyID($dwempid);
$regmobile=$curremp[0]['regmobile'];
$name=$curremp[0]['name'];
$email=$curremp[0]['email'];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://www.instamojo.com/api/1.1/payment-requests/');
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER,
            array("X-Api-Key:3656b1f0c5bed0c3aea8b204b55edc66",
                  "X-Auth-Token:2aec65aa7cbbb18369e75e85247c2b38"));
$payload = Array(
    'purpose' => 'Monthly Subscription',
    'amount' => '250' ,
    'phone' => $regmobile,
    'buyer_name' => $name,
    'redirect_url' => 'https://www.aideexpert.com/paymentsuccess?v_contractid='.$contractid,
    'send_email' => true,
    'webhook' => '',
    'send_sms' => true,
    'email' =>$email,
    'allow_repeated_payments' => false
);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
$response = curl_exec($ch);
curl_close($ch); 

$jsondata= json_decode($response,true);
//print_r($jsondata);
$success_url=$jsondata['payment_request']['longurl'];
//echo $response;
//echo "success".$success_url;

//header('location:',$success_url);
$sql = " INSERT INTO `payment` SET `dwempid` = '" . $dwempid. "',`_id`= '".$jsondata['payment_request']['id']."',`phone`=".$jsondata['payment_request']['phone'].",`email`='".$jsondata['payment_request']['email']."',`name`='".$jsondata['payment_request']['buyer_name']."',`amount`='".$jsondata['payment_request']['amount']."',`purpose`='".$jsondata['payment_request']['purpose']."',`status`='".$jsondata['payment_request']['status']."',`shorturl`='".$jsondata['payment_request']['shorturl']."',`longurl`='".$jsondata['payment_request']['longurl']."'";                                                                ;
 $result = dwqueryexec(__FUNCTION__, $sql);
 

echo "<script>location='".$jsondata['payment_request']['longurl']."';</script>";

//paymentRequest($dwempid,$regmobile,$name,$email);




?>
