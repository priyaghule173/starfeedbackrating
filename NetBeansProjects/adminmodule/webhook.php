<?php
/*
  Template Name: webhook
 */
if (empty($_SERVER['CONTENT_TYPE'])) {
    $_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded";
}



$ThisScript = basename(__FILE__, '.php');
//Common Functions
require_once(ABSPATH . '/dwpivr/common.php');
initconfig();

createConnection();
//$contractid=$_REQUEST['contractid'];
$id=$_REQUEST['v_payid'];

$contractid=$_REQUEST['v_contractid'];

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://www.instamojo.com/api/1.1/payments/'.$id.'/');
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER,
             array("X-Api-Key:3656b1f0c5bed0c3aea8b204b55edc66",
                  "X-Auth-Token:2aec65aa7cbbb18369e75e85247c2b38"));

$response = curl_exec($ch);
curl_close($ch); 
$jsondata= json_decode($response,true);
//print_r($jsondata);
//echo $response;

$sql = "UPDATE `payment` SET `status`='".$jsondata['payment']['status']."',`fees`='".$jsondata['payment']['fees']."',`createdts`='".$jsondata['payment']['created_at']."' WHERE `payment_id`='".$id."'";
//echo $sql;

$result = dwqueryexec(__FUNCTION__, $sql);


$std=$jsondata['payment']['created_at'];
$start=strtotime($std);
$startsub=date('Y-m-d',$start);
//$endsub=strtotime(date("Y-m-d",strtotime($startsub))."+1 month");
//$endsub=date('Y-m-d',strtotime($startsub,"+30 days"));
$endsub = date("Y-m-d", strtotime("$startsub +1 month"));

 $paymentinfo=array();
$sql="SELECT * FROM  `payment` WHERE `payment_id`='".$id."'";
      $result = dwqueryexec(__FUNCTION__, $sql);
       $paymentinfo=mysqli_fetch_all($result, MYSQLI_ASSOC);
       //print_r($paymentinfo);
       if(($paymentinfo[0]['status']=="Credit")&&($paymentinfo[0]['amount']==250)&&($paymentinfo[0]['payment_id']==$id))
           {
                              
               
                       $sql = "UPDATE `contracts` SET `substatus`='paid',`substartdate`='".$startsub."',`subenddate`='".$endsub."' WHERE `contractid`='" . $contractid . "'";           
                    
                       $result = dwqueryexec(__FUNCTION__, $sql);
                      
           }

           
           
           $annualendsub = date("Y-m-d", strtotime("$startsub +12 month"));
           
           
           
              if(($paymentinfo[0]['status']=="Credit")&&($paymentinfo[0]['amount']==3000)&&($paymentinfo[0]['payment_id']==$id))
              {
                   $sql = "UPDATE `contracts` SET `substatus`='paid',`substartdate`='".$startsub."',`subenddate`='".$annualendsub."' WHERE `contractid`='" . $contractid . "'";           
                    
                       $result = dwqueryexec(__FUNCTION__, $sql);
                      
              }
           
           
echo "<script>alert('Subscription is done');document.location='".site_url()."/dashboard'; </script>";

?>
