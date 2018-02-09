<?php
/* Template Name:SearchEmp */
/**
 * Copyright (c) AideExpert Pvt Ltd. - http://www.aideexpert.com/
 * searchemp.php: Search the employer */
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
    if ($_REQUEST['v_dwempid'] !== "") {
        $dwempid = $_REQUEST['v_dwempid'];
        $sql = "SELECT * FROM `dwemps` WHERE `dwempid` ='" . $dwempid . "'";
    } 
    else if ($_REQUEST["v_name"] !== "") {
       
        $name=$_REQUEST["v_name"];
         
         $sql = "SELECT * from `dwemps` WHERE `name` LIKE '%" . $name . "%'";
    //echo $sql;
    }
    else if ($_REQUEST["v_regmobile"] !== "") {

        $regmobile = $_REQUEST["v_regmobile"];
        $sql = "SELECT * from `dwemps` WHERE `regmobile`='" . $regmobile . "'";
    }

    $result = dwqueryexec(__FUNCTION__, $sql);
    $currentdw = mysqli_fetch_all($result, MYSQLI_ASSOC);
    //If Sql ERROR, give alert and go back 
    if ($GLOBALS['conn']->error) {
        //Error
        echo "<script>alert('Oops! Something went wrong. Please Search again..!!!');document.location='".site_url()."/searchemp'; </script>";
               // echo site_url() . '/searchemp' ;

    }
    //Else If No DW found, give alert and go back
    else if (count($currentdw) == 0) {

        echo "<script>alert('Oops! Did not find matching Employer. Please search again.');document.location='".site_url()."/searchemp'; </script>";
   
                //echo site_url() . '/searchemp' ;

        
    }
}
?>
<html>
    <head>
        <title>Search Employer</title>
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
                     
                     
                               

                     get_header(); ?>

    <body>
        <div class="container-fluid">
            <h1 class="text-center">Search Employer</h1>
            <form class="form-horizontal demo-form" name="search" data-parsley-validate="" method="POST"action="<?php echo get_permalink(); ?>">
                <!--action="action"-->
                <!--first form-->
                <div class="form-group">
                    <label class="control-label col-md-2">Emp Id</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="v_dwempid" id="v_dwempid" placeholder="Enter employer id." >
                    </div> 
                </div>

                <div class="form-group">

                    <label class="control-label col-md-2">Emp Name</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="v_name" placeholder="Enter Name here." >
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2">Mobile</label>
                    <div class="col-md-4">
                        <input type="number" class="form-control" name="v_regmobile" placeholder="Enter Phone Number." data-parsley-minlength="10" data-parsley-maxlength="10">
                    </div>
                </div>
                <div class="form-group">
                    <input type="reset" class="next btn btn-info pull-left" value="Reset"/>
                    <input type="submit" id="search" name="search" value="Search" class="btn btn-info pull-right">
                </div>
                <div class="col-xs-12">
                    <div class="table-responsive">           
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sno</th>
                                    <th>Name</th>
                                    <th>EmpId</th>
                                    <th>Doj</th>
                                    <th>Pincode</th>
                                    <th>MobileNo</th>
                                    <th>Email</th>
                                    <th>Area</th>
                                    <th>Add/Edit Emp </th>
                                    <th>Activation</th>
                                                                      
                                </tr>
                            </thead>
                            <tbody>
                                                         
                                <tr>
                                    <?php
                                    for ($mydw = 1; $mydw <= count($currentdw); $mydw++) {
                                        ?>   
                                        <th scope="row"><?php echo $mydw; ?></th>
                                        <th ><?php echo $currentdw[$mydw - 1]['name'] ?>  </th>
                                        <td><?php echo $currentdw[$mydw - 1]['dwempid'] ?></td>
                                        <td><?php echo $currentdw[$mydw - 1]['doj'] ?></td>
                                        <td><?php echo $currentdw[$mydw - 1]['pincode'] ?></td>
                                        <td><?php echo $currentdw[$mydw - 1]['regmobile'] ?></td>
                                        <td><?php echo $currentdw[$mydw - 1]['email'] ?></td>
                                        <td><?php echo $currentdw[$mydw - 1]['area'] ?></td>

                                        <td><a href="<?php echo site_url() . '/editemployer?v_dwempid='.$currentdw[0]['dwempid'] .'&v_name='.$currentdw[0]['name']. '&v_regmobile='.$currentdw[0]['regmobile'];?>&search=Search"><span class="glyphicon glyphicon-pencil" data-toggle="tooltip" title="Edit Employee"></span></a>
                                                                                 &emsp; <a href="<?php echo site_url() . '/searchcontract';?>"<span class="glyphicon glyphicon-search" data-toggle="tooltip" title="Search Contract"></span></a>
                                        </td>
                                        <?php
                                        if ($currentdw[$mydw - 1]['activeflag'] == 1) {
                                            echo '<td><button class="glyphicon glyphicon-thumbs-up"   style="font-size: 24px; color: #108db7;" data-toggle="tooltip" title="Active"></button></td>';
                                        } else {
                                            echo '<td><button class="glyphicon glyphicon-thumbs-down"   style="font-size: 24px; color: #108db7;" title="Inactive"></nutton></td>';
                                        }
                                        ?>
                                        
                                      
                                    <?php } ?>
                                </tr>
                               
                                                        </tbody>
                        
                        </table> 
                    </div>
                </div>


            </form>
        </div>

                     <?php get_footer(); ?>

    </body>
</html>
<?php
// Wrap up script
closeAll();
?>