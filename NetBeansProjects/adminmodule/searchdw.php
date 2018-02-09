<?php

/* Template Name:SearchDw */
/**
 * Copyright (c) AideExpert Pvt Ltd. - http://www.aideexpert.com/
 * searchdw.php: search the Domestic Worker record
 */
if (empty($_SERVER['CONTENT_TYPE'])) {
    $_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded";
}

$ThisScript = basename(__FILE__, '.php');
$libpath = dirname(__FILE__);
require_once(ABSPATH . '/dwpivr/common.php');
require_once('dwreglib.php');
if(!check_dwaccess('administrator')) {
    auth_redirect();
    
}

initconfig();
//connecton will be created
createConnection();
//Search by regid or dwid or regmobile
$currentdw = array();

if (isset($_REQUEST['search'])) {
    // print_r($_REQUEST);
    if ($_REQUEST['v_regid'] !== "") {
        $regid = $_REQUEST['v_regid'];
        $sql = "SELECT * FROM `dws` WHERE `regid` ='" . $regid . "'";
    } else if ($_REQUEST["v_dwid"] !== "") {
        $dwid = $_REQUEST["v_dwid"];
        $sql = "SELECT * from `dws` WHERE `dwid`='" . $dwid . "'";
    } else if ($_REQUEST["v_name"] !== "") {
       
        $name=$_REQUEST["v_name"];
         
         $sql = "SELECT * from `dws` WHERE `name` LIKE '%" . $name . "%'";
    //echo $sql;
    }
     else if ($_REQUEST["v_regmobile"] !== "") {

        $regmobile = $_REQUEST["v_regmobile"];
        $sql = "SELECT * from `dws` WHERE `regmobile`='" . $regmobile . "'";
    }
    else if ($_REQUEST["v_area"] !== "") {
       
        $area=$_REQUEST["v_area"];
         
         $sql = "SELECT * from `dws` WHERE `area` LIKE '%" . $area . "%'";
    //echo $sql;
    }
    
    else if ($_REQUEST["v_groupnm"] !== "") {
       
        $groupnm=$_REQUEST["v_groupnm"];
         
         $sql = "SELECT * from `dws` WHERE `groupname` LIKE '%" . $groupnm . "%'";
    //echo $sql;
    }
     else if ($_REQUEST["v_leadernm"] !== "") {
       
        $leadernm=$_REQUEST["v_leadernm"];
         
         $sql = "SELECT * from `dws` WHERE `supportname` LIKE '%" . $leadernm . "%'";
    //echo $sql;
    }
    else if ($_REQUEST["v_leaderid"] !== "") {
       
        $leaderid=$_REQUEST["v_leaderid"];
         
         $sql = "SELECT * from `dws` WHERE `dwsupportid` LIKE '%" . $leadeid . "%'";
    //echo $sql;
    }
    

    $result = dwqueryexec(__FUNCTION__, $sql);
    $currentdw = mysqli_fetch_all($result, MYSQLI_ASSOC);
    //print_r($currentdw);
    //If Sql ERROR, give alert and go back 
    $link = $_SERVER['HTTP_REFERER'];

    if ($GLOBALS['conn']->error) {
        //Error
        echo "<script>alert('Oops! Something went wrong. Please Search again..!!!');document.location='".site_url()."/searchdw'; </script>";
    
          //   echo site_url() . '/searchdw' ;

    }
    //Else If No DW found, give alert and go back
    else if (count($currentdw) == 0) {

        echo "<script>alert('Oops! Did not find matching domestic worker. Please search again.');document.location='".site_url()."/searchdw';</script>";
        //echo site_url() . '/searchdw' ;

        }
}
?>
<html>
    <head>
        <title>Search Domestic Worker</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
               <link rel="stylesheet" type="text/css" href="<?php wp_enqueue_style( 'my-style', get_stylesheet_directory_uri() . '/dwcss.css', false, '1.0', 'all' ); // Inside a child theme
?>"/>
   
        <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src= "https://cdn.jsdelivr.net/parsleyjs/2.7.1/parsley.min.js"></script>

    </head>
    <?php  
               

    get_header();?>

    <body>
        <div class="container-fluid">
            <h1 class="text-center">Search Domestic Worker</h1>
            <form class="form-horizontal demo-form" name="search" data-parsley-validate="" method="POST"action="<?php echo get_permalink(); ?>">
                <!--action="action"-->
                <!--first form-->
               
                <div class="form-group">
                    <label class="control-label col-md-2">Registration Id</label>
                    <div class="col-md-4">
                        <input type="number" class="form-control" name="v_regid" id="v_regid" placeholder="Enter registration id." >
                    </div> 
                
                    <label class="control-label col-md-2">PAN/Aadhar/Voter Id</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="v_dwid" id="v_dwid" placeholder="Enter Id No." >
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2">Name</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="v_name" id="v_name" placeholder="Enter  Name." >
                    </div>
                
                    <label class="control-label col-md-2">Mobile</label>
                    <div class="col-md-4">
                     <input type="number" class="form-control" name="v_regmobile" id="v_regmobile" placeholder="Enter Mobile No." data-parsley-minlength="10" data-parsley-maxlength="10">   </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2">groupname</label>
                    <div class="col-md-4">
                     <input type="text" class="form-control" name="v_groupnm" id="v_groupnm" placeholder="Enter Group Name.">   </div>
                
                    <label class="control-label col-md-2">group leader name</label>
                    <div class="col-md-4">
                     <input type="text" class="form-control" name="v_leadernm" id="v_leadernm" placeholder="Enter Leader Name." >   </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2">Area</label>
                    <div class="col-md-4">
                     <input type="text" class="form-control" name="v_area" id="v_area" placeholder="Enter Area ." >   </div>
               <label class="control-label col-md-2">group leader id</label>
                    <div class="col-md-4">
                     <input type="text" class="form-control" name="v_leaderid" id="v_leaderid" placeholder="Enter Leader id." >   </div>
                
                
                </div>
                
                <div class="form-group">
                     <div class="container">
                         <div class="left">
                             <input type="reset" class="next btn btn-info " value="Reset"/></div>
                             <div class="centre">
                               <a href="<?php echo site_url() .'/registerdw';?> ">  <input type="button" id="reg" name="register" value="New Register"   class="btn btn-info"></a></div>
              <div class="right">
                                 <input type="submit" name="search" value="Search" class="btn btn-info">
                </div>
                     </div>
                </div>
                <div class="col-xs-12">
                    <div class="table-responsive">           
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>SerialNo</th>
                                    <th>Name</th>
                                    <th>Regid</th>
                                    <th>DwId</th>
                                    <th>Address</th>
                                    <th>MobileNo</th>
                                    <th>GroupName</th>
                                    <th>LeaderName</th>
                                    <th>Actions</th>
                                    <th>Activation</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                    <?php
                                    for ($mydw = 1; $mydw <= count($currentdw); $mydw++) {
                                        ?>  
                                <tr>
                                        <th scope="row"><?php echo $mydw; ?></th>
                                        <th ><?php echo $currentdw[$mydw - 1]['name'] ?>  </th>
                                        <td><?php echo $currentdw[$mydw - 1]['regid'] ?></td>
                                        <td><?php echo $currentdw[$mydw - 1]['dwid'] ?></td>
                                        <td><?php echo $currentdw[$mydw - 1]['address1'] ?></td>
                                        <td><?php echo $currentdw[$mydw - 1]['regmobile'] ?></td>
                                        <td><?php echo $currentdw[$mydw - 1]['groupname'] ?></td>
                                        <td><?php echo $currentdw[$mydw - 1]['supportname'] ?></td>

                                        <td> <a href="<?php echo site_url() .'/editdw ?v_regid='.$currentdw[0]['regid'].'&v_dwid='. $currentdw[0]['dwid'].'&v_regmobile='.$currentdw[0]['regmobile']; ?>&search=Search"><span class='glyphicon glyphicon-pencil' data-toggle="tooltip" title="Update"></span></a>
                                            &emsp;<a href="<?php echo site_url() . '/updatebenefits ?v_dwid='.$currentdw[0]['dwid']. '&v_regmobile='.$currentdw[0]['regmobile'] ;?>&search=Search"><span class="glyphicon glyphicon-edit" data-toggle="tooltip" title="Benefits"></span></a>
&nbsp;
                                        </td>
                                        <?php
                                        if ($currentdw[$mydw - 1]['activeflag'] == 1) {
                                            echo '<td><button class="glyphicon glyphicon-thumbs-up"   style="font-size: 24px; color: #108db7;" data-toggle="tooltip" title="Active"></button></td>';
                                        } else {
                                            echo '<td><button class="glyphicon glyphicon-thumbs-down"   style="font-size: 24px; color: #108db7;" title="Inactive"></nutton></td>';
                                        }
                                        ?> </tr>
                                        
                                      
                                    <?php } ?>
                               
                                </tr>
                            </tbody>
                        </table> 
                    </div>
                </div>


            </form>
        </div>

    <?php    get_footer();?>

    </body>
</html>
<?php
// Wrap up script
closeAll();
?>




