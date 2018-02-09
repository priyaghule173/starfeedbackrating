<?php /* Template Name: CreateTasks */ ?>
<?php
/**
 * Copyright (c) AideExpert Pvt Ltd. - http://www.aideexpert.com/
 *  
 * tasks-create.php: Create Tasks.
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

<head>

</head>


<?php get_header(); ?>

<?php
dwerror_log(__FUNCTION__ . "REQUEST=" . print_r($_REQUEST, TRUE));
if (isset($_REQUEST['contractid'])) {
    $contractid = $_REQUEST["contractid"];
}

//First pass, initialise variables
$taskname = "";
//$contractid = "";
$jobdate = "";
$priority = 0;
$status = 0;

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
    $priority = (int) $_REQUEST["priority"];
    $status = 0; //By default

    if ($contractid == "") {
        $taskid = NULL;
    } else {
        $taskid = createTask($contractid, $taskname, $jobdate, $priority, $status);
    }

    if ($taskid == NULL) {
        $message = "Something went wrong. Please try again.";
    } else {
        
        
        
        
       echo "<script>document.location='". site_url() . '/dashboard?contractid=' . $contractid."&insert=Save'</script>"; 
        
        
        
        
    }
    //Reset variables
    $taskname = "";
    $today = date('Y-m-d');
    $jobdate = $today;
    $priority = 0;
    $status = 0;
}

//Check if contractid is set, or called directly
$nodw_message = "";
if (!isset($_REQUEST["contractid"]) || $_REQUEST["contractid"] == "") {
    $nodw_message = "Please choose a Domestic Help";
}

//Prepare the form display variables
if ($priority == 1) {
    $disphPriority = "checked";
    $displPriority = "";
} else {
    $disphPriority = "";
    $displPriority = "checked";
}
?>

<html>
    <head>
        <title>Manage Tasks</title>
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
       
        
        <style type="text/css">
            .center-btn{
                position: relative;
    min-height: 1px;
    padding-right: 200px;
    padding-left: 150px;
    
  
}
            
        </style>
        

    </head>
   
    <body>
        
        <div class="top-content">

            <div class="inner-bg">
                <div class="container-fluid">

                    <h1 class="text-center">Add New Tasks</h1>
                    <br>
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 form-box">

                            <form class="form-horizontal demo-form" enctype="multipart/form-data" name="empregistration" data-parsley-validate="" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                <!--action="action"-->
                                <div class="form-top">
                                    <div class="form-top-left">
                                        <h3 class="text-center">Enter The Task details</h3>
                                       
                                    </div>
                                </div>
        <div class="wrap">
            <?php
            if ($nodw_message != "") {
                echo ("<h2>" . $nodw_message . "</h2>");
            } else {
                ?>
           
                
    <?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
                <form class="form-horizontal demo-form" name="create" data-parsley-validate="" method="POST"action="<?php echo get_permalink(); ?>">
                   

                      <div class="form-bottom">
                    <div class="form-group">

                        <label class="control-label col-md-4">Task</label>
                        <div class="col-md-8">
                            <input type="text" name="taskname" value="<?php echo $taskname; ?>" class="ss-field-width" />
                            <span class="error">* <?php echo $taskErr; ?></span>

                        </div>
                    </div>

                    <div class="form-group">

                        <label class="control-label col-md-4">Date</label>
                        <div class="col-md-8">
                            <input id= "date" type="date" name="jobdate" value="<?php echo $jobdate; ?>" class="ss-field-width" />   
                        </div>
                    </div>

                                 <div class="form-group">

                                        <label class="control-label col-md-4">Priority</label>
                                        <div class="col-md-8">
                                            <input type="radio" name="priority" value=1 <?php echo $disphPriority; ?> class="ss-field-width" />High &nbsp;&nbsp;&nbsp;
                            <input type="radio" name="priority" value=0 <?php echo $displPriority; ?> class="ss-field-width" />Low</td>
                    <input type="hidden" name="status" value="<?php echo $status; ?>"  />
                    <input type="hidden" name="contractid" value="<?php echo $contractid; ?>"/>
                                        
                                        </div>
                                    </div>

  <div class="form-group">
                    <div class="col-md-12 text-center">
                
                 <input type="submit" name="insert" value="Save" class="btn btn-info">
                     </div>   
                  </div>
                          </div>
                    </div> 
                </form>
<?php } ?>
       
 </div>
            </div>
        </div>
            </div>
</div>
<?php get_footer(); ?>

    </body>
</html>

<?php closeAll(); ?>
