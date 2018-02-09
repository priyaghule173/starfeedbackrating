<?php /* Template Name: UpdateTasks */ ?>
<?php

/**
 * Copyright (c) AideExpert Pvt Ltd. - http://www.aideexpert.com/
 *  
 * tasks-update.php: Update or Delete today's tasks.
 *
 */
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
initconfig(); 

createConnection();
?>




<?php get_header(); ?>

<?php

    
    //Gather variable values from the form
    $id = $_REQUEST["id"];
    $taskname = $_REQUEST["taskname"];
    $jobdate = $_REQUEST["jobdate"];
    $priority=(int) $_REQUEST["priority"];
    $status= (int) $_REQUEST["status"];
    $contractid = $_REQUEST["contractid"];

    $taskErr="";

    dwerror_log(__FUNCTION__."REQUEST=".print_r($_REQUEST, TRUE));

    //Update    
    if (isset($_REQUEST['update'])) {
        
        //We need at least a non blank name of the task...
        if (empty($_REQUEST["taskname"])) {
            $taskErr = "Task title is required";
        } else {
            $taskname = $_REQUEST["taskname"];        
            updateTask($id,$contractid,$taskname,$jobdate,$priority,$status);
        }
    }
    //delete
    else if (isset($_REQUEST['delete'])) {
        deleteTask($id);
    } else {//selecting value to update	
        //$tasks = $dwdb->get_results($dwdb->prepare("SELECT _id,taskname from $table_name where id=%s", $id));
        //foreach ($tasks as $s) {
        //    $name = $s->name;
        //}
    }

    //Prepare the form display variables
    if ( $priority==1 ) {$disphPriority="checked"; $displPriority="";} else {$disphPriority=""; $displPriority="checked";}
    if ( $status == 1 ) {$disppStatus = ""; $dispdStatus = "checked";} else {$disppStatus = "checked"; $dispdStatus = "";}
    
    dwerror_log(__FUNCTION__.":dispPriority=".$dispPriority." disppStatus=".$disppStatus. ":dispdStatus=".$dispdStatus);

    //Add Datepicker
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');


    ?>

<html>
    <head>
        <title>Update Task</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
              
        <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"></script>

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src= "https://cdn.jsdelivr.net/parsleyjs/2.7.1/parsley.min.js"></script>


    </head>
<body>

    <div class="wrap">
        

        <?php if ($_REQUEST['delete']) {
       
        
       echo "<script>alert(' Your Task is Deleted Successfully');document.location='".site_url()."/dashboard?contractid=".$contractid . "' </script>";

        
         } else if ($_REQUEST['update']) { 
             
 echo "<script>alert(' Your Task is Updated');document.location='".site_url()."/dashboard?contractid=".$contractid . "' </script>";

             } else { ?>
            
             <div class="top-content">

            <div class="inner-bg">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 form-box">

                            <form class="form-horizontal demo-form" enctype="multipart/form-data" name="empregistration" data-parsley-validate="" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                <!--action="action"-->
                                <div class="form-top">
                                    <div class="form-top-left">
                                        <h3 class="text-center">Update Tasks</h3>
                                       
                                    </div>
                                </div>
            <div class="form-bottom">
               
                    <div class="form-group">

                        <label class="control-label col-md-4">Task</label>
                        <div class="col-md-8">
                            <input type="text" name="taskname" value="<?php echo $taskname; ?>" class="ss-field-width" />
                            <span class="error">
                                <br>* Write task in hindi. we will read it to your helper. <?php echo $taskErr; ?></span>

                        </div>
                    </div>

                       <div class="form-group">

                        <label class="control-label col-md-4">Date</label>
                        <div class="col-md-8">
                            <input id="date" type="date" name="jobdate" value="<?php echo $jobdate; ?>" class="ss-field-width" />
                           
                        </div>
                    </div>

                    <div class="form-group">

                        <label class="control-label col-md-4">Priority</label>
                        <div class="col-md-8">
                            <input type="radio" name="priority" value=1 <?php echo $disphPriority;?> class="ss-field-width" />High
                        <input type="radio" name="priority" value=0 <?php echo $displPriority;?> class="ss-field-width" />Low
                    
                        </div>
                    </div>
                        
                    <div class="form-group">

                        <label class="control-label col-md-4"> Status</label>
                        <div class="col-md-8">
                            <input type="radio" name="status" value=1 <?php echo $dispdStatus; ?>/>Done
                        <input type="radio" name="status" value=0 <?php echo $disppStatus; ?>/>Pending
                   
                        </div>
                    </div>
                   
                <div class="form-group">
                    <div class="col-md-12 text-center">
                <input type='submit' name="update" value='Save' class='button'> &nbsp;&nbsp;
                <input type='submit' name="delete" value='Delete' class='button' onclick="return confirm('Are You Sure?')">
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
                </div> 

    
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
