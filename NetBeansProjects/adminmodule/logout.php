<?php

/* Template Name:logout */?>
<?php
add_filter('allowed_redirect_hosts','allow_ms_parent_redirect');
function allow_ms_parent_redirect($allowed)
{
    $allowed[] = 'aideexpert.com';
    return $allowed;
}
?>
<a href="<?php echo wp_logout_url( 'https://aideexpert.com' ); ?>">Logout</a>
<?php wp_logout(); ?>
<html>
    <!--
 * Copyright (c) AideExpert Pvt Ltd. - http://www.aideexpert.com/
 *  
 * registerdw.html : Employer Registration field...
 *

 *  -->
    <head>
        <title>Aideexpert.com</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php wp_enqueue_style( 'my-style', get_stylesheet_directory_uri() . '/dwcss.css', false, '1.0', 'all' ); // Inside a child theme
?>"/>
        
                <script src="https://cdn.jsdelivr.net/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/parsleyjs/2.7.1/parsley.min.js"></script>                        
        <!-- <script src="javascript.js" type="text/javascript"></script>                 -->
          </head>
<?php get_header(); ?>
    <body> </div>
<?php get_footer(); ?>
    </body>
</html>