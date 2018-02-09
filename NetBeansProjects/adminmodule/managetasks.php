<?php /* Template Name: ManageTasks */ ?>
<?php
/**
 * Copyright (c) AideExpert Pvt Ltd. - http://www.aideexpert.com/
 *  
 * managetasks.php: List Tasks -CRUD.
 *
 */
if (empty($_SERVER['CONTENT_TYPE'])) {
    $_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded";
}

if (!is_user_logged_in()) {
    auth_redirect();
}

$ThisScript = basename(__FILE__, '.php');
//Common Functions
require_once(ABSPATH . '/dwpivr/common.php');
initconfig();

createConnection();
?>



<?php get_header(); ?>

<?php
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
    $allcontracts = getempidContracts($thisemployer['dwempid']);
    if (count($allcontracts) > 0) {
        $contractsRegistered = TRUE;
        
        
        
     
        if (isset($_REQUEST['dwupdate'])) {
            $contractid = $_REQUEST['contractid'];
            
            

            
            
        } else {
            //The first one is default
            $contractid = $allcontracts[0]['contractid'];
        }
    } else {
        $contractsRegistered = FALSE;
        $contractid = NULL;
    }
} else {
    // Employer not registered in dwpivr module            
    $userRegistered = FALSE;
}


//We have all the contracts in $allcontracts
dwerror_log(__FUNCTION__ . ":AllContracts=" . print_r($allcontracts, TRUE));
$today = date('Y-m-d');
dwerror_log(__FUNCTION__ . ":Contractid=" . $contractid);

if ($userRegistered && $contractsRegistered) {
    $alltasks = getallTasks($contractid);
} else {
    $alltasks = array();
}

dwerror_log(__FUNCTION__ . "Alltasks=" . print_r($alltasks, TRUE));
?>
<html>
    <head>
        <title>Manage Tasks</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?php wp_enqueue_style('my-style', get_stylesheet_directory_uri() . '/dwcss.css', false, '1.0', 'all'); // Inside a child theme
?>"/>
        <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src= "https://cdn.jsdelivr.net/parsleyjs/2.7.1/parsley.min.js"></script>
        <style type="text/css">
            .center-btn{
                position: relative;
                min-height: 1px;
                padding-right: 200px;
                padding-left: 15px;
            }
 .table-striped > tbody > tr:nth-child(2n+1) > td, .table-striped > tbody > tr:nth-child(2n+1) > th {
   background-color: #9dd3d4 !important;
}
        </style>


    </head>






    <body>
        <div class="container-fluid">
            <div class="wrap">
                <h1 class="text-center">Manage Tasks</h1>


                <form class="form-horizontal demo-form" name="search" data-parsley-validate="" method="POST"action="<?php echo get_permalink(); ?>">

                    <table class='wp-list-table widefat fixed striped posts'>
                        <tr>
                            <th class="manage-column ss-list-width">Select Domestic Help</th><th>&nbsp;</th>
                        </tr>
                        <?php if (!$contractsRegistered) { ?>
                            <td class="manage-column ss-list-width"><?php echo "No Contracted Domestic Help Found"; ?></td>
                        <?php } else {
                            ?>
                            <tr><td>
                                    <?php
                                    foreach ($allcontracts as $row) {
                                        dwerror_log(__FUNCTION__ . ":Row=" . $row['contractid'] . ":" . $row['dwname']);
                                        ?>
                                        <input type="radio" name="contractid" value="<?php echo $row['contractid']; ?>" <?php
                                        if ($contractid == $row['contractid']) {
                                            echo " checked ";
                                        }
                                        ?>><?php echo $row['dwname']; ?><br>
    <?php } ?>
                                </td>
                                <td>

                                    <div class="center-btn">

                                        <input type="submit" name="dwupdate" value="Submit" class="btn btn-info">
                                        &nbsp;&nbsp;
    <?php if ($userRegistered) { ?>
                                            <div class="tablenav top">
                                                <div class="alignleft actions">
                                                    <a href="<?php echo site_url() . '/createtasks?contractid=' . $contractid; ?>"><input type="button" id="add" name="contract" value="Add New Task"   class="btn btn-info"></a>
                                                </div>
                                                <br class="clear">
                                            </div>

    <?php } ?>


                                    </div>






                                </td>
                            </tr>

<?php } ?>
                    </table>


                    <div class="col-xs-16">
                        <div class="table-responsive">           
                            <table class="table table-striped">
                                <thead><tr>
                                        <th>Status</th>
                                        <th>Task</th>
                                        <th>Date</th>
                                        <th><i class="fa fa-star-o" aria-hidden="true"  ></i></th>
                                        

                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>



                                        <?php if (count($alltasks) == 0) { ?>
                                            <td class="manage-column ss-list-width"><?php echo "No tasks found for today"; ?></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                                            <?php
                                        } else {

                                            foreach ($alltasks as $row) {
                                                ?>
<td class="manage-column ss-list-width"><?php
                                                    if (($row['status']) == 1) {
                                                        ?>
                                                        <i class="fa fa-check-square-o" aria-hidden="true" data-toggle="tooltip" title="Done"></i>
                                                        <?php
                                                    } else {
                                                        ?>

                                                        <i class="fa fa-square-o" aria-hidden="true" data-toggle="tooltip" title="Pending"></i>

                                                        <?php
                                                    }
                                                    ?>



                                                </td>
                                                <td class="manage-column ss-list-width"><a href="<?php echo site_url() . '/updatetasks?id=' . $row['_id'] . '&taskname=' . $row['taskname'] . '&priority=' . $row['priority'] . '&status=' . $row['status'] . '&jobdate=' . $row['jobdate'] . '&contractid=' . $contractid; ?>"><?php echo $row['taskname']; ?></td></a></td>
                                                

                                                             

                                                <td class="manage-column ss-list-width"><?php echo date('d M', strtotime($row['jobdate']));  ?></td>
                                                <td class="manage-column ss-list-width"><?php
                                            if (($row['priority']) == 1) {
                                                    ?>
                                                        <span class="tooltiptext" data-toggle="tooltip" title="High"> <i class="fa fa-star" aria-hidden="true"  ></i></span>
                                                        <?php
                                                    } else {
                                                        ?>


                                                        <i class="fa fa-star-o" aria-hidden="true" data-toggle="tooltip" title="Low"></i>
                                                        <?php
                                                    }
                                                    ?>          
                                                </td>
                                                


                                                



                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>




                    </div>
                </form>
            </div>
        </div>

<?php get_footer(); ?>







    </body>
</html>


<?php closeAll(); ?>


