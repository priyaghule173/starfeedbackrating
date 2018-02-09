
<?php  
/* Template Name:AddContract */
/**
 * Copyright (c) AideExpert Pvt Ltd. - http://www.aideexpert.com/
 *  
 * addcontract: Add The Contract of Dw and employer
 *

 *  */

require_once(ABSPATH . '/dwpivr/common.php');
require_once('dwreglib.php');

if(!check_dwaccess('administrator')) {
    auth_redirect();
    
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


                    <?php
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
                    $currentdw1 = array();
                //    print_r($_REQUEST);
                //if (isset($_REQUEST['add']))
                //{
                    echo "<div class='form-group'>";

                    //Display Employer- dropdown or name
                    echo "<label class='control-label col-md-2'>Employer</label>";
                    echo"<div class='col-md-4'>";
                    
                    //Check if we have Employer ID
                    if (!isset($_REQUEST['v_dwempid']) || $_REQUEST['v_dwempid']=="")
                    {
                        //Find all employers
                        $allemployers=getallEMP();
                        sort($allemployers);
                        //print_r($allemployers);
                        // Create a employer dropdown
                        echo "<select name='v_dwempid' id='mySelect' class='form-control' required='' onChange='check(this);'>";
                        echo "<option value=''>----------Select------</option>";
                        
                        for ($row = 0; $row < count($allemployers) ; $row++) {
                            
                                $dwempid = $allemployers[$row]['dwempid'];
                                
                               $name = $allemployers[$row]['name'];
                              
                                //print_r(allemployers);
                                echo '<option value="' . $dwempid . '"> ' . $name . '</option>';
                            }
                            echo "</select>";

                    } else {
                        // Display employer Name
                        $myemployer=getEMPbyID($_REQUEST['v_dwempid']);
                        //print_r($myemployer);
                        
                        echo "<b>".$myemployer[0]['name']."</b>";
                    }
                    
                    echo "</div>";
                    //Employer display ends
                    

                    //Display DW- dropdown or name
                    echo "<label class='control-label col-md-2'>Domestic Worker</label>";
                    echo"<div class='col-md-4'>";
 
                    //Check if we have DW ID
                    if (!isset($_REQUEST['v_dwid']) || $_REQUEST['v_dwid']=="")
                    {
                        //Find all employers
                        $alldws=getallDW();
                       
                        $name=$alldws[$row]['name'];
                       
// Create a employer dropdown
                        echo "<select name='v_dwid' id='mySelect' class='form-control' required='' onChange='check(this);'>";
                        echo "<option value=''>----------Select------</option>";
                        
                        for ($row = 0; $row < count($alldws) ; $row++) {

                                unset($id, $name);
                                $dwid = $alldws[$row]['dwid'];
                                $name = $alldws[$row]['name'];
                                  
                                echo '<option value="' . $dwid . '"> ' . $dwid . ' - ' . $name . '</option>';
                            }
                            echo "</select>";

                    } else {
                        // Display employer Name
                        $mydw=getDWbydwid(($_REQUEST['v_dwid']));
                        //print_r($mydw);
                        echo "<b>". $mydw['name']."</b>";
                    }
                    echo "</div>";
                    echo "</div>";
                    //DW display ends
                    
                    
                    if (isset($_REQUEST['addc'])) {
                        $dwid = $_REQUEST['v_dwid'];
                        $sql = " INSERT INTO `contracts` SET `dwid` = '" . $dwid . "' ,";

                        $sql .= gencontractSql();
                        
                        $result = dwqueryexec(__FUNCTION__, $sql);
                        //if successfull
                      if ($GLOBALS['conn']->error) {
                            //Error
                            echo "<script>alert(' Oops! something went wrong. Please enter data again.');document.location='".site_url()."/searchcontract'; </script>";
                       
                             //echo site_url() . '/searchcontract' ;
                            
                            
                      } else {

                            echo "<script>alert('Contract Added successfully.  ');document.location='".site_url()."/searchcontract'; </script>";
                         //echo site_url() . '/searchcontract' ;
                            
                      }
                    }
                    ?>

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
     <input  class="form-control" type="time" name="v_dailyendtime" placeholder="click to show timepicker"  >  
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