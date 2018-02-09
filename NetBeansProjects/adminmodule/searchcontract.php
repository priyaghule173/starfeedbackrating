<?php
/* Template Name:SearchContract */
/**
 * Copyright (c) AideExpert Pvt Ltd. - http://www.aideexpert.com/
 * searchcontract.php: Search contract
 */
if (empty($_SERVER['CONTENT_TYPE'])) {
    $_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded";
}

$ThisScript = basename(__FILE__, '.php');
$libpath = dirname(__FILE__);
require_once(ABSPATH . '/dwpivr/common.php');

require_once('dwreglib.php');
if (!check_dwaccess('administrator')) {
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
        $sql = "SELECT * FROM `fullcontracts` WHERE `dwempid` ='" . $dwempid . "'";
    } else if ($_REQUEST["v_dwid"] !== "") {
        $dwid = $_REQUEST["v_dwid"];
        $sql = "SELECT * from `fullcontracts` WHERE `dwid`='" . $dwid . "'";
    } else if ($_REQUEST["v_contractid"] !== "") {
        $dwid = $_REQUEST["v_contractid"];
        $sql = "SELECT * from `fullcontracts` WHERE `contractid`='" . $contractid . "'";
    }


    $result = dwqueryexec(__FUNCTION__, $sql);

    $currentdw = mysqli_fetch_all($result, MYSQLI_ASSOC);
    //If Sql ERROR, give alert and go back 
    if ($GLOBALS['conn']->error) {
        //Error
        echo "<script>alert('Oops! Something went wrong. Please Search again..!!!');document.location='" . site_url() . "/searchcontract'; </script>";
        //echo site_url() . '/searchcontract';
    }
    //Else If No DW found, give alert and go back
    else if (count($currentdw) == 0) {
        echo "<script>alert('do not find please add contract');document.location='" . site_url() . "/searchcontract';</script>";
        //echo site_url() . '/addcontract';
    }
}
?>

<html>
    <head>
        <title>Search Contract</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?php wp_enqueue_style('my-style', get_stylesheet_directory_uri() . '/dwcss.css', false, '1.0', 'all'); // Inside a child theme
?>"/>



        <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src= "https://cdn.jsdelivr.net/parsleyjs/2.7.1/parsley.min.js"></script>

    </head>
    <?php get_header(); ?>
    <body>

        <div class="container-fluid">
            <h1 class="text-center">Search Contract</h1>
            <div class="col-md-12">
                <form class="form-horizontal demo-form" name="searchcontract" data-parsley-validate="" method="POST"action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    <!--action="action"-->
                    <!--first form-->
                    <div class="form-group">
                        <label class="control-label col-md-2">Emp Id</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="v_dwempid" id="v_dwempid" placeholder="Enter employer id." >
                        </div> 
                    </div>

                    <div class="form-group">

                        <label class="control-label col-md-2">Dw Id</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="v_dwid" placeholder="Enter domestic worker Id." >
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="container">
                            <div class="left">
                                <input type="reset" class="next btn btn-info " value="Reset"/></div>
                            <div class="centre">
                                <a href="<?php echo site_url() . '/addcontract'; ?>">  <input type="button" id="reg" name="register" value="New Contract"  class="btn btn-info"></a>
                            </div>
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
                                        <th>Sno</th>
                                        <th>DwId</th>
                                        <th>ContractId</th>
                                        <th>Emp Name</th>
                                        <th>Dw Name</th>
                                        <th>Dw Mobile</th>
                                        <th>Emp Mobile</th>
                                        <th>Add/Edit contracts</th>
                                        <th>Activation</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php
                                        for ($mydw = 1; $mydw <= count($currentdw); $mydw++) {
                                            ?>   
                                            <th scope="row"><?php echo $mydw; ?></th>
                                            <th ><?php echo $currentdw[$mydw - 1]['dwid'] ?>  </th>
                                            <th ><?php echo $currentdw[$mydw - 1]['contractid'] ?>  </th>
                                            <td><?php echo $currentdw[$mydw - 1]['empname'] ?></td>
                                            <td><?php echo $currentdw[$mydw - 1]['dwname'] ?></td>
                                            <td><?php echo $currentdw[$mydw - 1]['dwmobile'] ?></td>
                                            <td><?php echo $currentdw[$mydw - 1]['empmobile'] ?></td>

                                            <td>

                                                <a href="<?php
                                                echo site_url() . '/addcontract?v_dwempid=' . $currentdw[0]['dwempid'] .
                                                '&v_dwid=' . $currentdw[0]['dwid'] . '&v_empname=' . $currentdw[0]['empname'];
                                                ?> &search=Search"><span class="glyphicon glyphicon-plus" data-toggle="tooltip" title="Add Contract"></span></a>

                                                &emsp;<a href="<?php echo site_url() . '/editcon?v_contractid=' .$currentdw[$mydw - 1]['contractid'] . '&v_dwempid=' . $currentdw[0]['dwempid'] . '&v_dwid=' . $currentdw[0]['dwid'] ?>&search=Search"><span class="glyphicon glyphicon-pencil" data-toggle="tooltip" title="Edit contract"></span></a>


                                            </td>
                                            <?php
                                            if ($currentdw[$mydw - 1]['activeflag'] == 1) {
                                                echo '<td><button class="glyphicon glyphicon-thumbs-up"   style="font-size: 24px; color: #108db7;" data-toggle="tooltip" title="Active"></button></td>';
                                            } else {
                                                echo '<td><button class="glyphicon glyphicon-thumbs-down"   style="font-size: 24px; color: #108db7;" title="Inactive"></button></td>';
                                            }
                                            ?>

                                        </tr>

                                    </tbody>
                                <?php } ?>
                            </table> 
                        </div>
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
