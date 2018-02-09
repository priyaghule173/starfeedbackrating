
<?php  
/* Template Name:contract by emp */
/**
 * Copyright (c) AideExpert Pvt Ltd. - http://www.aideexpert.com/
 *  
 * addcontract: Add The Contract of Dw and employer
 *

 *  */
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
require_once(ABSPATH . '/dwpivr/common.php');
require_once('dwreglib.php');
$userRegistered = TRUE;
$contractsRegistered = TRUE;




$thisuser = wp_get_current_user();
dwerror_log(__FUNCTION__ . ":Curr wp user=" . $thisuser->user_email);

//Find Employer with logged in user's email address
//Find all contracts for the employer
//construct a dropdown with names of DW's for this employer
// Find employer record from wp user email
$sql = "SELECT * FROM `dwemps` WHERE `email` = '" . $thisuser->user_email . "'";
$result = dwqueryexec(__FUNCTION__, $sql);

if ($result->num_rows > 0) {
    // Find the employer who is calling
    $thisemployer = $result->fetch_assoc();
   // print_r($thisemployer);
    $allcontracts = getempidContracts($thisemployer['dwempid']);
}
$currentdw=array();
$dwid=$_REQUEST['v_dwid'];
$currentdw=getDWbydwid($dwid);
//print_r($currentdw);

if (isset($_REQUEST['addc'])) {
                        $dwid = $_REQUEST['v_dwid'];
                        $sql = " INSERT INTO `contracts` SET `dwid` = '" . $dwid . "' ,";

                        $sql .= gencontractSql();
                        
                        $result = dwqueryexec(__FUNCTION__, $sql);

//$name=$thisemployer['name'];
  if ($GLOBALS['conn']->error) {
        //Error
        echo "<script>alert(' Oops! something went wrong. Please enter contract details again.'); document.location='".site_url()."/contractbyemp'</script>";
      
        
    } else {
            echo "<script>alert(' contract successfully created..'); document.location='".site_url()."/paysubscription'</script>";
  
        }
}
?>

<html>
    <head>
        <title>AideExpert.com</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
                <link rel="stylesheet" type="text/css" href="<?php wp_enqueue_style( 'my-style', get_stylesheet_directory_uri() . '/dwcss.css', false, '1.0', 'all' ); // Inside a child theme
?>"/>
        
        
                
        <script src="https://cdn.jsdelivr.net/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/parsleyjs/2.7.1/parsley.min.js"></script>                
        <script type="text/javascript">
            function check(elem) {
                document.getElementById('mySelect1').disabled = !elem.selectedIndex;
            }
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.10.0/jquery.timepicker.min.js"></script>

        
    </head>
    <?php
    
    get_header(); ?>
    <body>
        <div class="container-fluid">
            <h1 class="text-center"> Contract</h1>
            <br>
            <div class="col-md-12">
                <form class="form-horizontal demo-form" enctype="multipart/form-data" name="contract Form" data-parsley-validate="" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">

<div class="form-group">

                        <label class="control-label col-md-2"> Employer Name</label>
                        <div class="col-md-4">
                            <input type="hidden"  name="v_dwempid"  value="<?php echo $thisemployer['dwempid'] ?>">

                            <input type="text" class="form-control" name="v_empname"  value="<?php echo $thisemployer['name'] ?>" required="">
                        </div>



                        <label class="control-label col-md-2"> Domestic Worker Name </label>
                        <div class="col-md-4">
                  <input type="hidden"  name="v_dwid"  value="<?php echo $currentdw['dwid'] ?>">

                            <input type="text" class="form-control" name="v_dwname"  value="<?php echo $currentdw['name'];?>" id="v_doj" >
                        </div>
                    </div>
                   

                   <div class="form-group">

                        <label class="control-label col-md-2">Major Working Skills</label>

                        <div class="col-md-4">
                            <div class="checkbox">
                                <label ><input type="checkbox"  name="v_majorskills[]"value="DH" data-parsley-mincheck="1" required="">धुनी</label>

                                <label ><input type="checkbox" name="v_majorskills[]" value="PO">पोल्या</label>

                                <label><input type="checkbox" name="v_majorskills[]" value="BH" >भांडी</label>

                                <label ><input type="checkbox" name="v_majorskills[]" value="CO">स्वयपाक</label>
                                <label ><input type="checkbox" name="v_majorskills[]" value="EW">अधीक काम</label>
                                <label><input type="checkbox" name="v_majorskills[]" value="CH">मुलाची देखरेख</label>
                            </div> 
                        </div> 
                    </div>
                    <div class="form-group">

                        <label class="control-label col-md-2"> Start Date </label>
                        <div class="col-md-4">
                            <input type="date" class="form-control" name="v_startdate" required="">
                        </div>



                        <label class="control-label col-md-2"> End Date </label>
                        <div class="col-md-4">
                            <input type="date" class="form-control" name="v_enddate" id="v_doj" >
                        </div>
                    </div>

                    <div class="form-group">
<label class="control-label col-md-2">Select Daily Start  Time :</label>

                        <div class="col-md-4">
     <input  class="form-control" type="time" name="v_dailystarttime" placeholder="click to show timepicker"  >  
                        </div>
<label class="control-label col-md-2">Select Daily End  Time :</label>
                        <div class="col-md-4">
     <input  class="form-control" type="text" name="v_dailyendtime" placeholder="click to show timepicker"  >  
                        </div>
                    </div>
                    <div class="form-group">

                        <label class="control-label col-md-2">Salary</label>

                        <div class="col-md-4">
                            <input type="text" name="v_sallary" class="form-control" >
                        </div> 
                        <label class="control-label col-md-2"> Notes</label>

                        <div class="col-md-4">
                            <textarea  name="v_dwempnotes" class="form-control"  ></textarea>
                        </div> 
                    </div> 
                    <div class="form-navigation">
                        <input  type="reset" id="Reset" name="Reset" value="Reset" class="btn btn-primary pull-left" >

                        <input type="submit" id="submit" name="addc" value="AddContract" class="btn btn-primary pull-right">

                    </div>

                </form>
            </div>
        </div>
            <?php get_footer(); ?>

    </body>
</html>
<?php
// Wrap up script
closeAll();
?>