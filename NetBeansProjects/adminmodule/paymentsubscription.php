<?php
/*
  Template Name: paysubscription
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
$data_name=$_REQUEST['v_name'];
//echo $data_name;
//echo $dwempid; 
$curremp = getEMPbyID($dwempid);
 
 
 //print_r($curremp);
//$getatten = getRating('175');

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
    'buyer_name' => 'priya g',
    'redirect_url' => 'https://www.aideexpert.com/paymentsuccess/',
    'send_email' => true,
    'webhook' => 'https://www.aideexpert.com/webhook',
    'send_sms' => true,
    'email' =>'dev2@aideexpert.com',
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
echo "successgsdgfhjs".$success_url;

header('location:',$success_url);
?>



<html>
    <head>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">

        <link rel="stylesheet" type="text/css" href="<?php wp_enqueue_style('dwcss', get_stylesheet_directory_uri() . '/dwcss.css', false, '1.0', 'all'); // Inside a child theme
?>"/>
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://cdn.jsdelivr.net/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/parsleyjs/2.7.1/parsley.min.js"></script>     
        
    </head>
    <?php get_header(); ?>
    <body>
        <div class="container">
          <div class="col-md-6">
         <div class="form-group">
                     
                         
                             <div class="centre">
                                <a href="https://test.instamojo.com/@aideexperttest/lf54987ad9bd94816b41b1389d14e1d51/"> <input type="button" id="monthly" name="Monthly Subscription" value="Monthly Subscription"   class="btn btn-info"></a>
                             </div>
                     </div>   
         
                         
                         <div class="form-group">
                             <div class="centre">
                               <a href="https://www.instamojo.com/@aideexpert/lf4ac644338af4333a293f4ab1c4cdd76/">  <input type="button" id="monthly" name="Anual Subscription" value="Anual Subscription"   class="btn btn-info"></a>
                             </div>
                         </div>
         </div> </div>
                     </div>
                </div>
        <?php get_footer(); ?>
    </body>
</html>
<?php
// Wrap up script
closeAll();
?>