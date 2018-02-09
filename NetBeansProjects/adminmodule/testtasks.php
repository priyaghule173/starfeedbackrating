<?php /* Template Name: TestTasks */ ?>
<?php
/**
 * Copyright (c) AideExpert Pvt Ltd. - http://www.aideexpert.com/
 *  
 * managetasks.php: List Tasks -CRUD.
 *
 */
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>

    <!-- Latest compiled and minified CSS -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

      <!-- Optional theme -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
      
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesnt work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
      
<?php
if(empty($_SERVER['CONTENT_TYPE']))
{ 
  $_SERVER['CONTENT_TYPE'] = "application/x-www-form-urlencoded"; 
}

if ( !is_user_logged_in() ) {
   auth_redirect();
}

$ThisScript = basename(__FILE__, '.php');
//Common Functions
require_once(ABSPATH . '/dwpivr/common.php');
//require_once('/Library/WebServer/Documents/dwpivr/common.php');
initconfig(); 

createConnection();
?>

      
  </head>

    <? php get_header(); ?>
<?php

    dwerror_log(__FUNCTION__."REQUEST=".print_r($_REQUEST, TRUE));
    if (isset($_REQUEST['contractid'])) {$contractid = $_REQUEST["contractid"];}

        //First pass, initialise variables
        $taskname = "";
        //$contractid = "";
        $jobdate = "";
        $priority=0;
        $status=0;

    $taskErr = "";

    //insert
    if (isset($_REQUEST['insert']) && isset($_REQUEST["contractid"])) {

        //We need at least the name of the task...
        if (empty($_REQUEST["taskname"])) {
            $taskErr = "Task title is required";
        } else {
            $taskname = $_REQUEST["taskname"];        
        }
        $contractid = $_REQUEST["contractid"];
        $jobdate = $_REQUEST["jobdate"];
        $priority= (int) $_REQUEST["priority"];
        $status=0; //By default
        
        if ($contractid=="") {
            $taskid=NULL;
        } else {
            $taskid = createTask($contractid, $taskname, $jobdate, $priority, $status);
        }
        
        if ($taskid==NULL) {
            $message="Something went wrong. Please try again.";
        } else {
            $message="Task Added";
        }
        //Reset variables
        $taskname="";
        $today = date('Y-m-d');
        $jobdate = $today;
        $priority=0;
        $status=0;

    }
    
    //Check if contractid is set, or called directly
    $nodw_message="";
    if (!isset($_REQUEST["contractid"]) || $_REQUEST["contractid"] == "") {    
            $nodw_message = "Please choose a Domestic Help";
    }

    //Prepare the form display variables
    if ( $priority==1 ) {$disphPriority="checked"; $displPriority="";} else {$disphPriority=""; $displPriority="checked";}

    //Add Datepicker
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');


?>

    <body>
    <div class="wrap">
    <?php if ($nodw_message != ""){
        echo ("<h2>" . $nodw_message . "</h2>");
    } else {
    ?>
        <h2>Add New Task</h2>
        <?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
        <form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
            <p>Enter the Task Details</p>
            <table class='wp-list-table widefat fixed'>
                <tr>
                    <th class="ss-th-width">Task</th>
                    <td><input type="text" name="taskname" value="<?php echo $taskname; ?>" class="ss-field-width" />
                    <span class="error">* <?php echo $taskErr;?></span>
                    </td>
                    <th class="ss-th-width">Date</th>
                    <td><input id= "date" type="date" name="jobdate" value="<?php echo $jobdate; ?>" class="ss-field-width" />
                    </td>
                    <th class="ss-th-width">Priority</th>
                        <td><input type="radio" name="priority" value=1 <?php echo $disphPriority;?> class="ss-field-width" />High</td>
                        <td><input type="radio" name="priority" value=0 <?php echo $displPriority;?> class="ss-field-width" />Low</td>
                </tr>
                <tr>
                    <input type="hidden" name="status" value="<?php echo $status; ?>"  />
                </tr>
                <tr>
                    <th class="ss-th-width"></th>
                    <td><input type="hidden" name="contractid" value="<?php echo $contractid; ?>"/></td>
                </tr>
            </table>
            <input type='submit' name="insert" value='Save' class='button'>
        </form>
    <?php } ?>
    </div>


    <!-- jQuery (necessary for Bootstraps JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
      
    <!-- Latest compiled and minified JavaScript -->
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <?php get_footer(); ?>

  </body>
</html>


<?php closeAll(); ?>

<?php function add_datepicker_in_footer(){ ?>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#date').datepicker({
        dateFormat: 'dd-mm-yy'
    });
});
</script>
<?php
} // close add_datepicker_in_footer() here
//add an action to call add_datepicker_in_footer function
add_action('wp_footer','add_datepicker_in_footer',10);

