<?php
/*
  Template Name: paymentrequest
 */


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://test.instamojo.com/api/1.1/payment-requests/');
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER,
            array("X-Api-Key:test_9f61f89856b5350aff88f0f3010",
                  "X-Auth-Token:test_7bc26478fda73773426516302c3"));
$payload = Array(
    'purpose' => 'tenrupee',
    'amount' => '10',
    'phone' => '7744910789',
    'buyer_name' => 'Priya Ghule',
    'redirect_url' => 'https://www.aideexpert.com/paymentsuccess/',
    'send_email' => true,
    'webhook' => 'https://www.aideexpert.com/webhook',
    'send_sms' => true,
    'email' => 'dev2@aideexpert.com',
    'allow_repeated_payments' => false
);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
$response = curl_exec($ch);
curl_close($ch); 

$long_url= json_decode($response,true);
print_r($long_url);
$success_url=$long_url['payment_request']['longurl'];
echo $response;
echo "success".$success_url;

header('location:',$success_url);
?>