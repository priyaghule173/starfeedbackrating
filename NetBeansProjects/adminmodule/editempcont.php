<?php
/* Template Name:EditEmpContract */
/**
 * Copyright (c) AideExpert Pvt Ltd. - http://www.aideexpert.com/
 * editcontract.php: Edit the Contract Details
 */
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
if (isset($_REQUEST['Update'])) {
    $sql = 'UPDATE `contracts` SET ';
    $sql .= gencontractSql();
    $contractid = $_REQUEST['v_contractid'];
    $sql .= " WHERE `contractid`='" . $contractid . "'";

    $result = dwqueryexec(__FUNCTION__, $sql);
    
    if ($GLOBALS['conn']->error) {
        //Error
        echo "<script>alert(' Oops! something went wrong. Please enter the data again.');document.location='".site_url()."/searchemp';</script>";

        
        // echo site_url() . '/searchcontract' ;
        
        
    } else {

        echo "<script>alert('Contract is Updated successfully.'); document.location='".site_url()."/searchemp';</script>";
   
         //echo site_url() . '/searchcontract' ;
        
    }
}
//Search by regid or dwid or regmobile
$currentdw = array();


    // print_r($_REQUEST);
    
    
    // Find if dwempid is registered
            $dwempid = $_REQUEST['v_dwempid'];
            $contractid=$_REQUEST['v_contractid'];
           // print_r("dwempid=" . $dwempid);
            $currentdw = getEmpDWbydwid($dwempid);
            $currentdw= getIDbyContractid($contractid);
           // print_r($currentdw);
       
   
   // if($_REQUEST["v_contractid"] !== "") {
        $contractid = $_REQUEST["v_contractid"];
        $sql = "SELECT * from `contracts` WHERE `contractid`='" . $contractid . "'";
    
    $result = dwqueryexec(__FUNCTION__, $sql);
    //    print_r($result);
    $currentdw = mysqli_fetch_all($result, MYSQLI_ASSOC);
   
    $skills =array_map('trim',explode(",",$currentdw[0]['majorskills']));
                  
    


?>



<html>
    <head>
        <title>Edit Contract</title>
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
                        <label class="control-label col-md-2">Contract Id</label>

                        <div class="col-md-4">
                            <input type="text" class="form-control"  name="v_contractid" required="" readonly="" value="<?php echo $currentdw[0]['contractid'] ?>" >
                        </div>
                          
                            <label class="control-label col-md-2">Major Working Skills</label>
                            <div class="col-md-4">
                                <div class="checkbox">
                                    <label ><input type="checkbox"  name="v_majorskills[]"value="DH" <?php if(in_array("DH",$skills)){ echo "checked"; }?> >धुनी</label>

                                    <label ><input type="checkbox" name="v_majorskills[]" value="PO" <?php if(in_array("PO",$skills)){ echo "checked"; }?> >पोल्या</label>

                                    <label><input type="checkbox" name="v_majorskills[]" value="BH" <?php if(in_array("BH",$skills)){ echo "checked"; }?> >भांडी</label>
                                    <label ><input type="checkbox" name="v_majorskills[]" value="CO" <?php if(in_array("CO",$skills)){echo "checked"; }?>>स्वयपाक</label>
                                    <label ><input type="checkbox" name="v_majorskills[]" value="EW" <?php if(in_array("EW",$skills)){echo "checked"; }?>>अधीक काम</label>
                                    <label><input type="checkbox" name="v_majorskills[]" value="CH" <?php if(in_array("CH",$skills)){echo "checked"; }?> >मुलाची देखरेख</label>
                                </div> 
                            </div>
                        </div>        
                    <div class="form-group">

                        <label class="control-label col-md-2"> Start Date </label>
                        <div class="col-md-4">
                            <input type="date" class="form-control" name="v_startdate" value="<?php echo $currentdw[0]['startdate'] ?>">
                        </div>



                        <label class="control-label col-md-2"> End Date </label>
                        <div class="col-md-4">
                            <input type="date" class="form-control" name="v_enddate" id="v_doj" value="<?php echo $currentdw[0]['enddate'] ?>">
                        </div>
                    </div>

                    <div class="form-group">
<label class="control-label col-md-2">Select Daily Start  Time :</label>

                        <div class="col-md-4">
     <input  class="form-control" type="text" name="dailystarttime" placeholder="click to show timepicker" value="<?php echo $currentdw[0]['dailystarttime'] ?>" id="example1">  
                        </div>
<label class="control-label col-md-2">Select Daily End  Time :</label>
                        <div class="col-md-4">
     <input  class="form-control" type="text" name="v_dailyendtime" placeholder="click to show timepicker"  value="<?php echo $currentdw[0]['dailyendtime'] ?>" id="example2">  
                        </div>
                    </div>
                    
                   
                    
                    
                    <div class="form-group">

                        <label class="control-label col-md-2">Salary</label>

                        <div class="col-md-4">
                            <input type="text" name="v_sallary" class="form-control" value="<?php echo $currentdw[0]['sallary'] ?>">
                        </div> 
                        <label class="control-label col-md-2"> Notes</label>

                        <div class="col-md-4">
                            <textarea  name="v_dwempnotes" class="form-control" > <?php echo $currentdw[0]['dwempnotes'] ?></textarea>
                        </div> 
                    </div> 
                    <div class="form-navigation">
                        <a href="<?php echo site_url() . '/searchcontract' ; ?>"> <button  type="button" class="btn pull-left" ><font color="white">Cancel</font></button></a>

                        <input type="submit" id="Update" name="Update" value="Update" class="btn pull-right">

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