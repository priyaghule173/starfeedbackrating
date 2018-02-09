<?php
/* Template Name: Request Helper Admin
 */

if (empty($_SERVER['CONTENT_TYPE'])) {
    $_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded";
}

if (!is_user_logged_in()) {
    auth_redirect();
}
$ThisScript = basename(__FILE__, '.php');

$libpath = dirname(__FILE__);
//require_once ('empcommon.php');
require_once(ABSPATH . '/dwpivr/common.php');
require_once('dwreglib.php');
initconfig();
createConnection();

$userRegistered = TRUE;
$contractsRegistered = TRUE;


if (isset($_REQUEST['contractid'])) {
    $contractid = $_REQUEST['contractid'];
}


//$thisuser = wp_get_current_user();
//dwerror_log(__FUNCTION__ . ":Curr wp user=" . $thisuser->user_email);

//Find Employer with logged in user's email address
//Find all contracts for the employer
//construct a dropdown with names of DW's for this employer
// Find employer record from wp user email
//$sql = "SELECT * FROM `dwemps` WHERE `email` = '" . $thisuser->user_email . "'";
//$result = dwqueryexec(__FUNCTION__, $sql);
//
//if ($result->num_rows > 0) {
//    // Find the employer who is calling
//    $thisemployer = $result->fetch_assoc();
//    $allcontracts = getempidContracts($thisemployer['dwempid']);
//    $dwempid = $thisemployer['dwempid'];
//    // $dwid=$allcontracts[0]['dwid'];
//    if (count($allcontracts) > 0) {
//        $contractsRegistered = TRUE;
//
//
//
//        if (isset($_REQUEST['dwupdate'])) {
//
//            $contractid = $_REQUEST['contractid'];
//
//
////echo "<script>document.location='" . site_url() . "/dashboard?contractid=" . $contractid .  "'</script>";
//        } else {
//            if (!isset($_REQUEST['contractid'])) {
//                //The first one is default
//                $contractid = $allcontracts[0]['contractid'];
//            }
//        }
//    } else {
//        $contractsRegistered = FALSE;
//        $contractid = NULL;
//    }
//} else {
//    // Employer not registered in dwpivr module            
//    $userRegistered = FALSE;
//}
//

//We have all the contracts in $allcontracts
//dwerror_log(__FUNCTION__ . ":AllContracts=" . print_r($allcontracts, TRUE));
//$today = date('Y-m-d');
//dwerror_log(__FUNCTION__ . ":Contractid=" . $contractid);

$result = dwqueryexec(__FUNCTION__, $sql);
    
    $currentemp = mysqli_fetch_all($result, MYSQLI_ASSOC);
   // print_r($currentemp);

if (isset($_REQUEST['request'])) {

   $email=$_REQUEST['v_email']; 
   $sql = "SELECT * FROM `dwemps` WHERE `email` = '" . $email . "'";
   echo $sql;
$result = dwqueryexec(__FUNCTION__, $sql);
print_r($result);
if ($result->num_rows > 0) {
    // Find the employer who is calling
    $thisemployer = $result->fetch_assoc();
    $allcontracts = getempidContracts($thisemployer['dwempid']);
    $dwempid = $thisemployer['dwempid'];
// echo $dwempid;
    // $dwid=$allcontracts[0]['dwid'];
    if (count($allcontracts) > 0) {
        $contractsRegistered = TRUE;



        if (isset($_REQUEST['dwupdate'])) {

            $contractid = $_REQUEST['contractid'];


//echo "<script>document.location='" . site_url() . "/dashboard?contractid=" . $contractid .  "'</script>";
        } else {
            if (!isset($_REQUEST['contractid'])) {
                //The first one is default
                $contractid = $allcontracts[0]['contractid'];
            }
        }
    } else {
        $contractsRegistered = FALSE;
        $contractid = NULL;
    }
} else {
    // Employer not registered in dwpivr module            
    $userRegistered = FALSE;
}

dwerror_log(__FUNCTION__ . ":AllContracts=" . print_r($allcontracts, TRUE));
$today = date('Y-m-d');
dwerror_log(__FUNCTION__ . ":Contractid=" . $contractid);
 $dwempid = $thisemployer['dwempid'];
$sql = "SELECT * FROM `dwemps` WHERE `dwempid` = '" . $dwempid . "'";

$result = dwqueryexec(__FUNCTION__, $sql);
    
    $currentemp = mysqli_fetch_all($result, MYSQLI_ASSOC);
  // print_r($currentemp);

    $dwempid = $thisemployer['dwempid'];
    $sql = " INSERT INTO `request-helper` SET `dwempid` = '" . $dwempid . "' ,";


    $sql .= genReqHelperAdmin();

    echo $sql;
    $result = dwqueryexec(__FUNCTION__, $sql);
    $dwempid = $thisemployer['dwempid'];
$sql = " SELECT  * FROM  `request-helper` WHERE `dwempid` = '" . $dwempid . "' ";
    $result = dwqueryexec(__FUNCTION__, $sql);
    $currentrequest = mysqli_fetch_all($result, MYSQLI_ASSOC);
  //  print_r($currentrequest);
    
 $message="<b>Employer Details..</b>";
     $message.="<br>";
    $message .="<div class=table-responsive>";
     $message .="<table  class='table-bordered' width=100% border=1>";
      $message .="<thead>";
     
                                $message.="<tr>";
                                    $message.="<th>Name</th>";
                                    $message.="<th>Contact</th>";
                                     $message.="<th>Email</th>";
                                     
                                    $message.="</tr>";
                                     $message.="</thead>";
                                     $message.="<tr>";
                                    // echo $dwempid;
                                     //cho $currentemp[0]['dwempid'];
                                      $message.='<td><a href='.site_url() . '/viewemployer?v_dwempid='.$currentemp[0]['dwempid'].'>'.$currentemp[0]['name'].'</a></td>';

                                    //$message.='<td>'. $currentemp[0]['name'].'</td>';
                                    $message.='<td>'.$currentemp[0]['regmobile'].'</td>';
                                    $message.='<td>'.$currentemp[0]['email'].'</td>';
                              $message.="</tr>";
                             $message.="<tbody>";
                             $message.="</tbody>";
                        
                        $message.="</table> ";
                        $message.="</div>";
                         $message.="<br>";
                        
                        $message.=" <b>Helper Request Details..</b>";
                         $message .="<div class=table-responsive>";
     $message .="<table  class='table-bordered' width=100% border=1>";
      $message .="<thead>";
     
                                $message.="<tr>";
                                   
                                    $message.="<th>Area</th>";
                                     $message.="<th>Worktype</th>";
                                     $message.="<th>Address</th>";
                                     $message.="<th>dailystarttime</th>";
                                     $message.="<th>dailyendtime</th>";
                                     $message.="<th>pincode</th>";
                                     $message.="<th>landmark</th>";
                                     $message.="<th>weekoff</th>";
                                     $message.="<th>minbudget</th>";
                                     $message.="<th>maxbudget</th>";
                                     
                                     
                                    $message.="</tr>";
                                     $message.="</thead>";
                                     $message.="<tr>";
                                    // echo $dwempid;
                                     //cho $currentemp[0]['dwempid'];
                                    //  $message.='<td><a href='.site_url() . '/viewrequesthelper?v_reqhelperid='.$currentrequest[0]['reqhelperid'].'>'.$id.'</a></td>';
 $message.='<td>'.$currentrequest[0]['area'].'</td>';
                                    $message.='<td>'. $currentrequest[0]['worktype'].'</td>';
                                      $message.='<td>'. $currentrequest[0]['address'].'</td>';
  $message.='<td>'. $currentrequest[0]['dailystarttime'].'</td>';
 $message.='<td>'. $currentrequest[0]['dailyendtime'].'</td>';
 $message.='<td>'. $currentrequest[0]['pincode'].'</td>';
 $message.='<td>'. $currentrequest[0]['landmark'].'</td>';
 $message.='<td>'. $currentrequest[0]['weekoff'].'</td>';
 $message.='<td>'. $currentrequest[0]['minbudget'].'</td>';
 $message.='<td>'. $currentrequest[0]['maxbudget'].'</td>';
 

                                   
                                    
                             $message.="<tbody>";
                             $message.="</tbody>";
                        
                        $message.="</table> ";
                         $message.="</div>";
                        
                        
  $subj="HelperRequest-".$currentemp[0]['name']."@".$currentemp[0]['area'];
$typ="Looking for a New Domestic Help";
$ema="dev1@sandeepsuryavanshi.com";
$prio=1;
$stat=2;
$cc_mai=array("priyaghule173@gmail.com","punamnavale26@gmail.com","dev2@sandeepsuryavanshi.com");
 $id=(createTicket($message,$subj,$typ,$ema,$prio,$stat,$cc_mai));    
    
}
?>

<html>
    <head>
        <title>AideExpert.com</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
        <style type="text/css">
            .box{
              
                padding: 20px;
                display: none;
                margin-top: 20px;
            }
            
       
.button, input[type="button"], input[type="reset"], input[type="submit"] {
    /* box-shadow: none; */
  
    font-family: quick sand !important;
}
               
        </style>  
        <link rel="stylesheet" type="text/css" href="<?php wp_enqueue_style('my-style', get_stylesheet_directory_uri() . '/dwcss.css', false, '1.0', 'all'); // Inside a child theme
?>"/>


        <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>       
        <script src="https://cdn.jsdelivr.net/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/parsleyjs/2.7.1/parsley.min.js"></script>     
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.js"></script>
       
        <script type="text/javascript">
            $(document).ready(function () {
                $('#example1').timepicker();
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#example2').timepicker();
            });
        </script>

        <script type="text/javascript">
            $(document).ready(function () {
                $("#arealist").change(function () {
                    $(this).find("option:selected").each(function () {
                        var optionValue = $(this).attr("value");
                        console.log(optionValue);
                        if (optionValue) {
                            //$(".box").not("." + optionValue).hide();
                            $(".box").show();
                        } else {
                            $(".box").hide();
                        }
                    });
                }).change();
            });
        </script>
    </head>
<?php get_header(); ?>
    <body>
        <div class="container-fluid">
            <h1 class="text-center"><b>Welcome</b></h1>
            <div class="col-md-12">
                <form class="form-horizontal demo-form" enctype="multipart/form-data" name="contract Form" data-parsley-validate="" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    <div class="form-group">
                        <label class="control-label col-md-2">Area</label>
                        <div class="col-md-4">
<?php $allarea = getallArea(); ?>
                            <select name='v_area' id='arealist' class='form-control' required=''>
<?php
echo "<option value=''>----------Select------</option>";
for ($row = 0; $row < count($allarea); $row++) {

    $area = $allarea[$row]['area'];

    echo '<option value="' . $area . '"> ' . $area . '</option>';
}
?>
                            </select>
                        </div>
                    </div>
                    <br>
                    
                    <div class="red box">
                     <div class="form-group">
                        <label class="control-label col-md-2">Major Working Skills</label>
                        <div class="col-md-4">
                            <div class="checkbox">
                                <label ><input type="checkbox"  name="v_worktype[]"value="CL" data-parsley-mincheck="1" required="">Clothes</label>

                                <label ><input type="checkbox" name="v_worktype[]" value="DU">Dusting</label>

                                <label><input type="checkbox" name="v_worktype[]" value="WU" >Wash utensils</label>

                                <label ><input type="checkbox" name="v_worktype[]" value="CO">Cooking</label>
                                <label ><input type="checkbox" name="v_worktype[]" value="CC"> Child care</label>
                                <label><input type="checkbox" name="v_worktype[]" value="CL"> Cleaning</label>
                            </div> 
                        </div>
                              
                        <label class="control-label col-md-2">Address </label>
                        <div class="col-md-4">
                            <textarea class="form-control" rows="3" name="v_address" id="v_address1" placeholder="Enter Address One." required=""></textarea>
                        </div>
                     </div>
 <div class="form-group">
                        <label class="control-label col-md-2">Employer Name :</label>

                        <div class="col-md-4">
                            <input  class="form-control" type="name" name="v_name" placeholder="Enter Employer Name"  
                                    >  
                        </div>
                        <label class="control-label col-md-2">Employer Mobile :</label>
                        <div class="col-md-4">
                            <input  class="form-control" type="number" name="v_regmobile" placeholder="Enter phone number"
                                    >  
                        </div>
                    </div>


               <div class="form-group">
                        <label class="control-label col-md-2">Employer Email</label>

                        <div class="col-md-4">
                            <input type="email" name="v_email" class="form-control" placeholder="Enter Employer Email"  required="" >
                        </div> 
                         <label class="control-label col-md-2">Pincode</label>

                        <div class="col-md-4">
                            <input type="text" name="v_pincode" class="form-control" placeholder="Enter 6 digit Pincode" data-parsley-minlength="6" data-parsley-maxlength="6" required="" >
                        </div> 

                    </div>


                    <div class="form-group">
                        <label class="control-label col-md-2">Select Daily Start  Time :</label>

                        <div class="col-md-4">
                            <input  class="form-control" type="time" name="v_dailystarttime" placeholder="click to show timepicker"  
                                    >  
                        </div>
                        <label class="control-label col-md-2">Select Daily End  Time :</label>
                        <div class="col-md-4">
                            <input  class="form-control" type="time" name="v_dailyendtime" placeholder="click to show timepicker"
                                    >  
                        </div>
                    </div>
                    

                    <div class="form-group">
                        <label class="control-label col-md-2">Landmark</label>

                        <div class="col-md-4">
                            <input type="text" name="v_landmark"  placeholder="Enter Landmark here" class="form-control" >
                        </div> 
                        <label class="control-label col-md-2">weekly Off</label>

                        <div class="col-md-4">
                            <input type="text" name="v_weekoff" placeholder=" Enter Weekly off"class="form-control" >
                        </div> 
                    </div>



                    <div class="form-group">
                        <label class="control-label col-md-2">Range Budget(min)</label>

                        <div class="col-md-4">
                            <input type="text" name="v_minbudget" placeholder="Minimum Budget" class="form-control" >
                        </div> 
                        <label class="control-label col-md-2">Range Budget(max)</label>

                        <div class="col-md-4">
                            <input type="text" name="v_maxbudget" placeholder="Maximum Budget" class="form-control" >
                        </div> 
                    </div>
                    <div class="form-navigation">
                        <input  type="reset" id="Reset" name="Reset" value="Reset" class="btn btn-primary pull-left" >

                        <input type="submit" id="submit" name="request" value="Request" class="btn btn-primary pull-right">

                    </div>
     </div>
                </form>




            </div>    
        </div>




<?php get_footer(); ?>
    </body>
</html>